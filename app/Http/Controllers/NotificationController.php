<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Gate, DB};
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $html = '';

        if (Gate::allows('do_update', 'naskah-pn-prodev')) {
            $notif = DB::table('notif as n')
                ->join('notif_detail as nd', function ($j) {
                    $j->on('n.id', '=', 'nd.notif_id')
                        ->where('nd.user_id', auth()->id())
                        ->where('nd.seen', '0');
                })
                ->join('penerbitan_naskah as pn', function ($j) {
                    $j->on('n.form_id', '=', 'pn.id')
                        ->where('pn.pic_prodev', auth()->id())
                        ->whereNull('deleted_at');
                })
                ->where('n.permission_id', 'ebca07da8aad42c4aee304e3a6b81001')
                ->whereNull('n.expired')
                ->select(DB::raw('nd.created_at as tgl_notif, pn.id, pn.judul_asli'))
                ->get();

            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $n->id) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>' . $n->judul_asli . '</i></strong> perlu dinilai (<strong>Prodev</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }

        if (Gate::allows('do_update', 'naskah-pn-editor')) {
            $notifPenilaian = DB::table('notif')
                ->where('type', 'Penilaian Naskah')->whereNull('expired')
                ->where('permission_id', '5d793b19c75046b9a4d75d067e8e33b2')->orderBy('form_id', 'desc')
                ->select('permission_id', 'form_id', 'created_at as ndate')->get();

            $idNaskah = [];
            $notifPenilaian = (object)collect($notifPenilaian)->map(function ($item, $key) use (&$idNaskah) {
                $idNaskah[$key] = $item->form_id;
                return $item;
            })->all();

            if (!is_null($notifPenilaian)) {
                $naskah = DB::table('penerbitan_naskah')->whereIn('id', $idNaskah)->whereNull('deleted_at')
                    ->orderBy('id', 'desc')->select('id', 'judul_asli')->get();
                foreach ($naskah as $key => $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notifPenilaian->{$key}->ndate, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $n->id) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>' . $n->judul_asli . '</i></strong> perlu dinilai (<strong>Editor</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }

        if (Gate::allows('do_update', 'naskah-pn-setter')) {
            $notifPenilaian = DB::table('notif')
                ->where('type', 'Penilaian Naskah')->whereNull('expired')
                ->where('permission_id', '33c3711d787d416082c0519356547b0c')->orderBy('form_id', 'desc')
                ->select('permission_id', 'form_id', 'created_at as ndate')->get();

            $idNaskah = [];
            $notifPenilaian = (object)collect($notifPenilaian)->map(function ($item, $key) use (&$idNaskah) {
                $idNaskah[$key] = $item->form_id;
                return $item;
            })->all();

            if (!is_null($notifPenilaian)) {
                $naskah = DB::table('penerbitan_naskah')->whereIn('id', $idNaskah)->whereNull('deleted_at')
                    ->orderBy('id', 'desc')->select('id', 'judul_asli')->get();
                foreach ($naskah as $key => $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notifPenilaian->{$key}->ndate, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $n->id) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>' . $n->judul_asli . '</i></strong> perlu dinilai (<strong>Setter</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }

        if (Gate::allows('do_update', 'naskah-pn-mpemasaran')) {
            $notif = DB::table('notif as n')
                ->join('penerbitan_naskah as pn', function ($j) {
                    $j->on('n.form_id', '=', 'pn.id')
                        ->whereNull('pn.deleted_at');
                })
                ->where('n.permission_id', 'a213b689b8274f4dbe19b3fb24d66840')
                ->whereNull('n.expired')
                ->select(DB::raw('n.created_at as tgl_notif, pn.id, pn.judul_asli'))
                ->get();

            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $n->id) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>' . $n->judul_asli . '</i></strong> perlu dinilai (<strong>M.Pemasaran</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }
        if (Gate::allows('do_update', 'naskah-pn-mpenerbitan')) {
            $notif = DB::table('notif as n')
                ->join('penerbitan_naskah as pn', function ($j) {
                    $j->on('n.form_id', '=', 'pn.id')
                        ->whereNull('pn.deleted_at');
                })
                ->join('penerbitan_pn_stts as pns', function ($j) {
                    $j->on('pn.id', '=', 'pns.naskah_id');
                })
                ->where('n.permission_id', '12b852d92d284ab5a654c26e8856fffd')
                ->whereNull('n.expired')
                ->select(DB::raw('pns.tgl_pn_prodev as tgl_notif, pn.id, pn.judul_asli'))
                ->get();

            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $n->id) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>' . $n->judul_asli . '</i></strong> perlu dinilai (<strong>M.Penerbitan</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }

        if (Gate::allows('do_update', 'naskah-pn-direksi')) {
            $notifPenilaian = DB::table('notif')
                ->where('type', 'Penilaian Naskah')->whereNull('expired')
                ->where('permission_id', '8791f143a90e42e2a4d1d0d6b1254bad')->orderBy('form_id', 'desc')
                ->select('permission_id', 'form_id', 'created_at as ndate')->get();

            $idNaskah = [];
            $notifPenilaian = (object)collect($notifPenilaian)->map(function ($item, $key) use (&$idNaskah) {
                $idNaskah[$key] = $item->form_id;
                return $item;
            })->all();

            if (!is_null($notifPenilaian)) {
                $naskah = DB::table('penerbitan_naskah')->whereIn('id', $idNaskah)->whereNull('deleted_at')
                    ->orderBy('id', 'desc')->select('id', 'judul_asli')->get();
                foreach ($naskah as $key => $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $notifPenilaian->{$key}->ndate, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/naskah/melihat-naskah/' . $n->id) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Naskah <strong><i>' . $n->judul_asli . '</i></strong> perlu dinilai (<strong>Direksi</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }
        if (Gate::allows('do_approval', 'Dir. Operasional')) {
            $notif = DB::table('notif as n')
                ->join('order_ebook as oe', function ($j) {
                    $j->on('n.form_id', '=', 'oe.id');
                })
                ->where(function ($q) {
                    $q->where('n.type', 'Tolak Order E-Book')
                        ->orWhere('n.type', 'Terima Order E-Book');
                })
                ->where('n.permission_id', '4cea10b3a4434bc3b342407a78a9ab2a')
                ->whereNull('n.expired')
                ->select(DB::raw('n.created_at as tgl_notif, oe.id, oe.kode_order'))
                ->get();
            // dd($notif);
            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbbitan/order-cetak/detail?order=' . $n->id . '&naskah=') . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Kode order <strong><i>' . $n->kode_order . '</i></strong> perlu Anda tanggapi (<strong>Manajer Penerbitan</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }
        if (Gate::allows('do_approval', 'Penerbitan')) {
            $notif = DB::table('notif as n')
                ->join('order_cetak as po', function ($j) {
                    $j->on('n.form_id', '=', 'po.id');
                })
                ->join('order_cetak as po', function ($j) {
                    $j->on('n.form_id', '=', 'po.id');
                })
                ->where(function ($q) {
                    $q->where('n.type', 'Tolak Order E-Book')
                        ->orWhere('n.type', 'Terima Order E-Book');
                })
                ->where('n.permission_id', '171e6210418440a8bf4d689841d0f32c')
                ->whereNull('n.expired')
                ->select(DB::raw('n.created_at as tgl_notif, po.id, po.created_by, po.kode_order, po.judul_buku'))
                ->get();
            // dd($notif);
            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/order-ebook/detail?order=' . $n->id . '&naskah=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Kode order <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" perlu Anda tanggapi (<strong>Manajer Stok</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }
        if (Gate::allows('do_approval', 'Marketing & Ops')) {
            $notif = DB::table('notif as n')
                ->join('order_cetak as po', function ($j) {
                    $j->on('n.form_id', '=', 'po.id');
                })
                ->where(function ($q) {
                    $q->where('n.type', 'Tolak Order E-Book')
                        ->orWhere('n.type', 'Terima Order E-Book');
                })
                ->where('n.permission_id', '4cea10b3a4434bc3b342407a78a9ab2a')
                ->whereNull('n.expired')
                ->select(DB::raw('n.created_at as tgl_notif, po.id, po.created_by, po.kode_order, po.judul_buku'))
                ->get();
            // dd($notif);
            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Kode order <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" perlu Anda tanggapi (<strong>Direktur Operasional</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }
        if (Gate::allows('do_approval', 'Keuangan')) {
            $notif = DB::table('notif as n')
                ->join('order_cetak as po', function ($j) {
                    $j->on('n.form_id', '=', 'po.id')
                        ->whereNull('deleted_at');
                })
                ->where(function ($q) {
                    $q->where('n.type', 'Tolak Order E-Book')
                        ->orWhere('n.type', 'Terima Order E-Book');
                })
                ->where('n.permission_id', '78712deb909d4d88af7f098c0fcf6857')
                ->whereNull('n.expired')
                ->select(DB::raw('n.created_at as tgl_notif, po.id, po.created_by, po.kode_order, po.judul_buku'))
                ->get();
            // dd($notif);
            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .=    '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" perlu Anda tanggapi (<strong>Direktur Keuangan</strong>).
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                }
            }
        }
        if (Gate::allows('do_approval', 'persetujuan-order-cetak')) {
            $notif = DB::table('notif as n')
                ->join('notif_detail as nd', function ($j) {
                    $j->on('n.id', '=', 'nd.notif_id')
                        ->where('nd.user_id', auth()->id())
                        ->where('nd.seen', '0');
                })
                ->join('produksi_order_cetak as po', function ($j) {
                    $j->on('n.form_id', '=', 'po.id')
                        ->whereNull('deleted_at');
                })
                ->join('produksi_penyetujuan_order_cetak as ppo', function ($j) {
                    $j->on('po.id', '=', 'ppo.produksi_order_cetak_id');
                })
                ->where(function ($q) {
                    $q->where('n.type', 'Persetujuan Order Buku Baru')
                        ->orWhere('n.type', 'Persetujuan Order Cetak Ulang Revisi')
                        ->orWhere('n.type', 'Persetujuan Order Cetak Ulang');
                })
                ->where('n.permission_id', '09179170e6e643eca66b282e2ffae1f8')
                ->whereNull('n.expired')
                ->select(DB::raw('nd.created_at as tgl_notif, nd.updated_at as tgl_update, nd.raw_data, po.id, po.created_by, po.kode_order, po.judul_buku, ppo.m_penerbitan, ppo.m_stok, ppo.d_operasional, ppo.d_keuangan, ppo.d_utama, ppo.d_operasional_act, ppo.d_keuangan_act, ppo.d_utama_act, ppo.pending_sampai'))
                ->get();

            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    if ($n->raw_data == 'Disetujui Cetak') {
                        if (is_null($n->m_stok)) {
                            if (auth()->id() == $n->d_operasional) {
                                $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                                $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-icon bg-success text-white">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh Manajer Penerbitan. Selanjutnya, silahkan Anda konfirmasi penyetujuan penerbitan order cetak.
                                    <div class="time text-primary">' . $craetedAt . '</div>
                                </div>
                            </a>';
                            }
                        } else {
                            if (auth()->id() == $n->d_operasional) {
                                $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                                $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-success text-white">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh Manajer Stok. Selanjutnya, silahkan Anda konfirmasi penyetujuan penerbitan order cetak.
                                        <div class="time text-primary">' . $craetedAt . '</div>
                                    </div>
                                </a>';
                            }
                        }
                        if (auth()->id() == $n->d_keuangan) {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-success text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh Direktur Operasional. Selanjutnya, silahkan Anda konfirmasi penyetujuan penerbitan order cetak.
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        } elseif (auth()->id() == $n->d_utama) {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-success text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh Direktur Keuangan. Selanjutnya, silahkan Anda konfirmasi penyetujuan penerbitan order cetak.
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        }
                    } elseif ($n->raw_data == 'Penyetujuan Cetak') {
                        $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                        $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-primary text-white">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" perlu Anda tanggapi.
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                    } elseif ($n->raw_data == 'Pending Cetak') {
                        if ($n->d_operasional_act == '2') {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" dipending oleh Direktur Operasional, sampai ' . Carbon::parse($n->pending_sampai)->translatedFormat('d F Y') . '
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        } elseif ($n->d_keuangan_act == '2') {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" dipending oleh Direktur Keuangan, sampai ' . Carbon::parse($n->pending_sampai)->translatedFormat('d F Y') . '
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        } elseif ($n->d_utama_act == '2') {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" dipending oleh Direktur Utama, sampai ' . Carbon::parse($n->pending_sampai)->translatedFormat('d F Y') . '
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        }
                    } elseif ($n->raw_data == 'Selesai Cetak') {
                        $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                        $html .= '<a href="' . url('penerbitan/order-cetak/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-info text-white">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" telah selesai disetujui, selanjutnya akan ke tahap produksi.
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                    }
                }
            }
        }
        if (Gate::allows('do_approval', 'persetujuan-order-ebook')) {
            $notif = DB::table('notif as n')
                ->join('notif_detail as nd', function ($j) {
                    $j->on('n.id', '=', 'nd.notif_id')
                        ->where('nd.user_id', auth()->id())
                        ->where('nd.seen', '0');
                })
                ->join('produksi_order_ebook as po', function ($j) {
                    $j->on('n.form_id', '=', 'po.id')
                        ->whereNull('deleted_at');
                })
                ->join('produksi_penyetujuan_order_ebook as ppo', function ($j) {
                    $j->on('po.id', '=', 'ppo.produksi_order_ebook_id');
                })
                ->where('n.permission_id', '171e6210418440a8bf4d689841d0f32c')
                ->whereNull('n.expired')
                ->select(DB::raw('nd.created_at as tgl_notif, nd.updated_at as tgl_update, nd.raw_data, po.id, po.created_by, po.kode_order, po.judul_buku, ppo.m_penerbitan, ppo.d_operasional, ppo.d_keuangan, ppo.d_utama, ppo.d_operasional_act, ppo.d_keuangan_act, ppo.d_utama_act, ppo.pending_sampai'))
                ->get();

            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    if ($n->raw_data == 'Disetujui') {
                        if (auth()->id() == $n->d_operasional) {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-icon bg-success text-white">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh Manajer Penerbitan. Selanjutnya, silahkan Anda konfirmasi penyetujuan penerbitan order e-book.
                                    <div class="time text-primary">' . $craetedAt . '</div>
                                </div>
                            </a>';
                        }
                        if (auth()->id() == $n->d_keuangan) {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-icon bg-success text-white">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh Direktur Operasional. Selanjutnya, silahkan Anda konfirmasi penyetujuan penerbitan order ebook.
                                    <div class="time text-primary">' . $craetedAt . '</div>
                                </div>
                            </a>';
                        } elseif (auth()->id() == $n->d_utama) {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-success text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh Direktur Keuangan. Selanjutnya, silahkan Anda konfirmasi penyetujuan penerbitan order e-book.
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        }
                    } elseif ($n->raw_data == 'Penyetujuan') {
                        $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                        $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-primary text-white">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" perlu Anda tanggapi.
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                    } elseif ($n->raw_data == 'Pending') {
                        if ($n->d_operasional_act == '2') {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" dipending oleh Direktur Operasional, sampai ' . Carbon::parse($n->pending_sampai)->translatedFormat('d F Y') . '
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        } elseif ($n->d_keuangan_act == '2') {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" dipending oleh Direktur Keuangan, sampai ' . Carbon::parse($n->pending_sampai)->translatedFormat('d F Y') . '
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        } elseif ($n->d_utama_act == '2') {
                            $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                            $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" dipending oleh Direktur Utama, sampai ' . Carbon::parse($n->pending_sampai)->translatedFormat('d F Y') . '
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                        }
                    } elseif ($n->raw_data == 'Selesai') {
                        $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_update, 'Asia/Jakarta')->diffForHumans();
                        $html .= '<a href="' . url('penerbitan/order-ebook/detail?kode=' . $n->id . '&author=' . $n->created_by) . '" class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-primary text-white">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" telah selesai disetujui, selanjutnya akan ke tahap produksi upload multimedia e-book.
                                <div class="time text-primary">' . $craetedAt . '</div>
                            </div>
                        </a>';
                    }
                }
            }
        }
        if (Gate::allows('do_update', 'ubah-lanjutan-data-produksi')) {
            $notif = DB::table('notif as n')
                ->join('notif_detail as nd', function ($j) {
                    $j->on('n.id', '=', 'nd.notif_id')
                        ->where('nd.user_id', auth()->id())
                        ->where('nd.seen', '0');
                })
                ->join('proses_produksi_cetak as ppc', function ($j) {
                    $j->on('n.form_id', '=', 'ppc.id');
                })
                ->join('produksi_order_cetak as po', function ($j) {
                    $j->on('po.id', '=', 'ppc.order_cetak_id')
                        ->whereNull('deleted_at');
                })
                ->where(function ($q) {
                    $q->where('n.type', 'Proses Produksi Order Cetak');
                })
                ->where('n.permission_id', 'a91ee437-1e08-11ed-87ce-1078d2a38ee5')
                ->whereNull('n.expired')
                ->select(DB::raw('nd.created_at as tgl_notif, nd.updated_at as tgl_update, nd.raw_data, po.id, po.created_by, po.kode_order, po.judul_buku, ppc.id as id_proses_produksi'))
                ->get();

            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .= '<a href="' . url('produksi/proses/cetak/detail?kode=' . $n->id . '&track=' . $n->id_proses_produksi) . '" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-warning text-dark">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul buku "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh jajaran direksi untuk dilanjutkan ke tahap produksi.
                            <div class="time text-primary">' . $craetedAt . '</div>
                        </div>
                    </a>';
                }
            }
        }
        if (Gate::allows('do_update', 'ubah-data-multimedia')) {
            $notif = DB::table('notif as n')
                ->join('notif_detail as nd', function ($j) {
                    $j->on('n.id', '=', 'nd.notif_id')
                        ->where('nd.user_id', auth()->id())
                        ->where('nd.seen', '0');
                })
                ->join('proses_ebook_multimedia as pem', function ($j) {
                    $j->on('n.form_id', '=', 'pem.id');
                })
                ->join('produksi_order_ebook as po', function ($j) {
                    $j->on('po.id', '=', 'pem.order_ebook_id')
                        ->whereNull('deleted_at');
                })
                ->where(function ($q) {
                    $q->where('n.type', 'Proses Produksi Order E-Book');
                })
                ->where('n.permission_id', 'd821a505-1e08-11ed-87ce-1078d2a38ee5')
                ->whereNull('n.expired')
                ->select(DB::raw('nd.created_at as tgl_notif, nd.updated_at as tgl_update, nd.raw_data, po.id, po.created_by, po.kode_order, po.judul_buku, pem.id as id_proses_produksi'))
                ->get();

            if (!$notif->isEmpty()) {
                foreach ($notif as $n) {
                    $craetedAt = Carbon::createFromFormat('Y-m-d H:i:s', $n->tgl_notif, 'Asia/Jakarta')->diffForHumans();
                    $html .= '<a href="' . url('produksi/proses/cetak/detail?kode=' . $n->id . '&track=' . $n->id_proses_produksi) . '" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-warning text-dark">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            Kode produksi <strong><i>' . $n->kode_order . '</i></strong> dengan judul e-book "<strong>' . $n->judul_buku . '</strong>" telah disetujui oleh jajaran direksi untuk dilanjutkan ke tahap upload.
                            <div class="time text-primary">' . $craetedAt . '</div>
                        </div>
                    </a>';
                }
            }
        }
        return $html;
    }

    public function viewAll()
    {
        $notification = DB::table('notif as n')
            ->join('notif_detail as nd', function ($j) {
                $j->on('n.id', '=', 'nd.notif_id')
                    ->where('nd.user_id', auth()->id());
            })
            ->get();

        // dd($notification);
        return view('manweb.notification.view_all', [
            'title' => 'All notification',
            'notification' => $notification
        ]);
    }
}
