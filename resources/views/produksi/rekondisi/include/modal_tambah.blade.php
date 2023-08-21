<div id="md_addRekondisi" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="titleAddRekondisi" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="titleAddRekondisi"><svg xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" width="24" height="24" aria-hidden="true">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path fill="currentColor"
                                d="M14 9V4H5v16h6.056c.328.417.724.785 1.18 1.085l1.39.915H3.993A.993.993 0 0 1 3 21.008V2.992C3 2.455 3.449 2 4.002 2h10.995L21 8v1h-7zm-2 2h9v5.949c0 .99-.501 1.916-1.336 2.465L16.5 21.498l-3.164-2.084A2.953 2.953 0 0 1 12 16.95V11zm2 5.949c0 .316.162.614.436.795l2.064 1.36 2.064-1.36a.954.954 0 0 0 .436-.795V13h-5v3.949z" />
                        </svg>
                        Tambah Data Rekondisi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTambahRekondisi">
                    <div class="modal-body">
                        <h6 class="pl-3 pt-3"><span class="beep"></span>Pilih naskah yang akan direkondisi <a href="javascript:void(0)" class="text-primary text-decoration-none" tabindex="0" role="button"
                            data-toggle="popover" data-trigger="focus" title="Informasi"
                            data-content="List berdasarkan naskah yang sudah selesai diproduksi sebelumnya.">
                            <abbr title="">
                            <i class="fas fa-info-circle me-3"></i>
                            </abbr>
                            </a></h6>
                        <hr>
                        <div id="dataModalRekondisi">

                        </div>

                    </div>
                    <div class="modal-footer" id="btnModalRekondisi">

                    </div>
                </form>
            </div>
        </div>
    </div>
