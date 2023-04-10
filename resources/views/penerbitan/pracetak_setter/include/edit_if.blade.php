<form id="fup_pracetakSetter" data-id="" data-id_pracov="">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kode Naskah:</th>
                                <td class="table-active text-right" id="kode"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Judul Final:</th>
                                <input type="hidden" name="judul_final" value="">
                                <td class="table-active text-right" id="judul_final"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Sub-Judul Final:
                                </th>
                                <td class="table-active text-right" id="sub_judul_final"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kelompok Buku:
                                </th>
                                <td class="table-active text-right" id="nama"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Imprint:
                                </th>
                                <td class="table-active text-right" id="imprint"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Penulis:</th>
                                <td class="table-active text-right" id="penulis"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Nama Pena:</th>
                                <td class="table-active text-right" id="nama_pena"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Format Buku:</th>
                                <td class="table-active text-right" id="format_buku"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Kertas Isi:</th>
                                <td class="table-active text-right" id="kertas_isi"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Isi Warna:</th>
                                <td class="table-active text-right" id="isi_warna"> </td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Isi Huruf:</th>
                                <td class="table-active text-right" id="isi_huruf"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Ukuran Asli:</th>
                                <td class="table-active text-right" id="ukuran_asli"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Sinopsis:</th>
                                <td class="table-active text-right" id="sinopsis"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Bullet:</th>
                                <td class="table-active text-right" id="bullet"></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Jumlah Halaman
                                    Final: <span class="text-danger">*</span></th>
                                <td class="table-active jml_hal_final"></td>
                                <td class="table-active text-left jmlHalColInput" id="jmlHalColInput" hidden>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Catatan:</th>
                                <td class="table-active catatan"></td>
                                <td class="table-active text-left catColInput" id="catColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Setter: <span class="text-danger">*</span></th>
                                <td class="table-active setter"></td>
                                <td class="table-active text-left setterColInput" id="setterColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Korektor:
                                    <span id="requiredKorektor"></span>
                                </th>
                                <td class="table-active korektor"></td>
                                <td class="table-active text-left korektorColInput" id="korektorColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Edisi Cetak:</th>
                                <td class="table-active edisi_cetak"></td>
                                <td class="table-active text-left edCetakColInput" id="edCetakColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Mulai Proses Copyright:</th>
                                <td class="table-active mulai_p_copyright"></td>
                                <td class="table-active text-left copyrightColInput" id="copyrightColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Selesai Proses Copyright:</th>
                                <td class="table-active selesai_p_copyright"></td>
                                <td class="table-active text-left copyrightSelColInput" id="copyrightSelColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">ISBN: <small class="text-danger">*Wajib ketika ingin menyelesaikan proses</small></th>
                                <td class="table-active isbn"></td>
                                <td class="table-active text-left isbnColInput" id="isbnColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Pengajuan Harga: <small class="text-danger">*Wajib ketika ingin menyelesaikan proses</small></th>
                                <td class="table-active pengajuan_harga"></td>
                                <td class="table-active text-left hargaColInput" id="hargaColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Proses Saat Ini: <span class="text-danger">*</span></th>
                                <td class="table-active proses_saat_ini"></td>
                                <td class="table-active text-left prosColInput" id="prosColInput" hidden></td>
                            </tr>
                            <tr>
                                <th class="table-secondary" style="width: 25%">Bulan: <span class="text-danger">*</span></th>
                                <td class="table-active bulan"></td>
                                <td class="table-active text-left bulanColInput" id="bulanColInput" hidden></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <div class="custom-control custom-switch">

            <input type="checkbox" name="proses" class="custom-control-input" id="prosesKerja" data-id="">
            <label class="custom-control-label mr-3 text-dark" for="prosesKerja" id="labelProses"></label>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
