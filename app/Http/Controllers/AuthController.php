<?php

namespace App\Http\Controllers;

use App\Events\UserLogEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function login()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
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

            $menus = [];
            foreach ($dataMenu as $d) {
                if ($d->level == 1) {
                    $menus[$d->id]['detail'] = [
                        'bagian_id' => $d->bagian_id,
                        'name_ab' => $d->name_ab,
                        'url' => $d->url,
                        'icon' => $d->icon,
                        'name' => $d->name,
                    ];
                }
                if ($d->level == 2) {
                    $menus[$d->parent_id]['child'][] = [
                        'url' => $d->url,
                        'icon' => $d->icon,
                        'name' => $d->name,
                    ];
                }
            }
            $menus_ = [];
            foreach ($menus as $key => $m) {
                $menus_[$m['detail']['name_ab']][$key] = $m;
            }
            $permissions = DB::table('user_permission as up')
                ->leftJoin('permissions as p', 'up.permission_id', '=', 'p.id')
                ->select(DB::raw('up.*, p.url, p.type, p.raw'))
                ->where('up.user_id', Auth::id())
                ->get();


            $userLog = [
                'users_id' => auth()->id(),
                'ip_address' => $request->getClientIp(true),
                'last_login' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'user_agent' => $request->server('HTTP_USER_AGENT'),
            ];
            event(new UserLogEvent($userLog));
            $request->session()->put('menus', $menus_);
            $request->session()->put('permissions', $permissions);

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
