<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Events\UserLogEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\{Auth, Hash, DB};

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function login()
    {
        return view('login',['title' => 'Login']);
    }
    public function doLogin(Request $request)
    {
        $request->session()->regenerate();
        $request->merge(array('email' => trim($request->email)));
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if (Auth::attempt($credentials)) {
            $dataMenu = DB::select("
                SELECT a.*, ab.name name_ab, ab.order_ab
                FROM access AS a
                JOIN (
                    SELECT a.id, a.parent_id, a.url
                    FROM user_permission AS up JOIN permissions AS p ON up.permission_id = p.id
                    JOIN access AS a ON p.access_id=a.id
                    WHERE up.user_id = '" . Auth::id() . "' AND p.`type` = 'Read'

                ) AS a2 ON a.id = a2.id
                LEFT JOIN access_bagian AS ab ON a.bagian_id = ab.id
                UNION
                SELECT a.*, ab.name name_ab, ab.order_ab
                FROM access AS a JOIN (
                    SELECT DISTINCT a.parent_id AS id
                    FROM user_permission AS up JOIN permissions AS p ON up.permission_id = p.id
                    JOIN access AS a ON p.access_id=a.id
                    WHERE up.user_id = '" . Auth::id() . "' AND p.`type` = 'Read' AND a.`level` = 2
                    ORDER BY a.name ASC
                ) AS a3 ON a.id = a3.id
                LEFT JOIN access_bagian AS ab ON a.bagian_id = ab.id
                ORDER BY order_ab,`level`, order_menu ASC
            ");
            // $menus = (object)[];
            foreach ($dataMenu as $d) {
                if ($d->level == 1) {
                    // ? OBJECT
                    // $menus[$d->id] =  (object)[
                    //         'detail' => (object)[
                    //             'bagian_id' => $d->bagian_id,
                    //             'name_ab' => $d->name_ab,
                    //             'url' => $d->url,
                    //             'icon' => $d->icon,
                    //             'name' => $d->name,
                    //     ]
                    // ];
                    // ? ARRAY
                    $menus[$d->id]['detail'] = [
                        'bagian_id' => $d->bagian_id,
                        'name_ab' => $d->name_ab,
                        'url' => $d->url,
                        'icon' => $d->icon,
                        'name' => $d->name,
                    ];
                }
                if ($d->level == 2) {
                    // ? OBJECT
                    // $menus[$d->parent_id] = (object)[
                    //         'child' => (object)[
                    //             'url' => $d->url,
                    //             'icon' => $d->icon,
                    //             'name' => $d->name,
                    //     ]
                    // ];
                    // ? ARRAY
                    $menus[$d->parent_id]['child'][] = [
                        'url' => $d->url,
                        'icon' => $d->icon,
                        'name' => $d->name,
                    ];
                }
            }
            // $menus_ = (object)[];
            foreach ($menus as $key => $m) {
                // dd($m->detail->name_ab);
                // $menus_[] = [
                //     $m->detail->name_ab => (object)[
                //         $key => $m
                //     ]
                // ];
                // $menus_[$m->detail->name_ab][$key] =$m;
                $menus_[$m['detail']['name_ab']][$key] = $m;
                // dd($menus_);
            }
            // dd($menus_);
            $permissions = DB::table('user_permission as up')
                ->leftJoin('permissions as p', 'up.permission_id', '=', 'p.id')
                ->select(DB::raw('up.*, p.url, p.type, p.raw'))
                ->where('up.user_id', Auth::id())
                ->get();

            // dd($permissions);
            $userLog = [
                'users_id' => auth()->id(),
                'ip_address' => $request->getClientIp(true),
                'last_login' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'user_agent' => $request->server('HTTP_USER_AGENT'),
            ];
            event(new UserLogEvent($userLog));
            $menus = json_encode($menus_);
            Redis::set('menus:'.session()->getId(), $menus);
            Redis::set('permissions:'.session()->getId(), $permissions);
            // $request->session()->put('menus', $menus_);
            // $request->session()->put('permissions', $permissions);

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request)
    {
        DB::table('users')->where('id',auth()->id())->update([
            'status_activity' => 'offline'
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function submitForgetPasswordForm(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );
            return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => "success", 'message' => 'Kami telah mengirimkan email tautan setel ulang kata sandi Anda!'])
                : response()->json(['status' => "error", 'message' => "Kami tidak dapat menemukan pengguna dengan alamat email tersebut."]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
