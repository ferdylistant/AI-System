<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function login() {
        return view('login');
    }

    public function doLogin(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if(Auth::attempt($credentials)) {
            $dataMenu = DB::select("
                SELECT a.*, ab.order_ab
                FROM access AS a
                JOIN (
                    SELECT a.id, a.parent_id, a.url
                    FROM user_permission AS up JOIN permissions AS p ON up.permission_id = p.id
                    JOIN access AS a ON p.access_id=a.id
                    WHERE up.user_id = '".Auth::id()."' AND p.`type` = 'Read'

                ) AS a2 ON a.id = a2.id
                LEFT JOIN access_bagian AS ab ON a.bagian_id = ab.id
                UNION
                SELECT a.*, ab.order_ab
                FROM access AS a JOIN (
                    SELECT DISTINCT a.parent_id AS id
                    FROM user_permission AS up JOIN permissions AS p ON up.permission_id = p.id
                    JOIN access AS a ON p.access_id=a.id
                    WHERE up.user_id = '".Auth::id()."' AND p.`type` = 'Read' AND a.`level` = 2
                    ORDER BY a.name ASC
                ) AS a3 ON a.id = a3.id
                LEFT JOIN access_bagian AS ab ON a.bagian_id = ab.id
                ORDER BY order_ab, order_menu,`level` ASC
            ");

            $menus = [];
            foreach($dataMenu as $d) {
                if($d->level == 1) {
                    $menus[$d->id]['detail'] = [
                        'bagian_id' => $d->bagian_id,
                        'url' => $d->url,
                        'icon' => $d->icon,
                        'name' => $d->name,
                    ];
                }
                if($d->level == 2) {
                    $menus[$d->parent_id]['child'][] = [
                        'url' => $d->url,
                        'icon' => $d->icon,
                        'name' => $d->name,
                    ];
                }

            }

            ## HardCode untuk Menu Header
            $menus_ = [];
            foreach($menus as $key => $m) {
                if($m['detail']['bagian_id'] == '04431b2b0e864cd4af41c87256cb92ef') {
                    $menus_['Dashboard'][$key] = $m;
                } elseif($m['detail']['bagian_id'] == '3f9dfd9391394a5fa10d835e0ebb341c') {
                    $menus_['Master Data'][$key] = $m;
                } elseif($m['detail']['bagian_id'] == '063203a5c5124b399ab76f8a03b93c0d') {
                    $menus_['Penerbitan'][$key] = $m;
                }elseif($m['detail']['bagian_id'] == 'f7e795b9ece54c6d82b0ed19f025a65e') {
                    $menus_['Manajemen Web'][$key] = $m;
                }elseif($m['detail']['bagian_id'] == '8a3ca046fb54492a86aaead53f36bec7') {
                    $menus_['Produksi'][$key] = $m;
                }

            }
            $permissions = DB::table('user_permission as up')
                                ->leftJoin('permissions as p', 'up.permission_id', '=', 'p.id')
                                ->select(DB::raw('up.*, p.url, p.type, p.raw'))
                                ->where('up.user_id', Auth::id())
                                ->get();



            $request->session()->put('menus', $menus_);
            $request->session()->put('permissions', $permissions);

            return redirect()->intended('/');
        }

        return back()->with('loginFailed', 'Login Failed.');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
