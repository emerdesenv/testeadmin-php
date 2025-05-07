                    </div>

                    <footer class="footer d-print-none">
                        <p class="text-muted">
                            <small class="float-right"><a target="_blank" class="text-muted" href="//objetiva.digital">Desenvolvido por Objetiva Digital</a> © <?= date("Y"); ?></small>
                        </p>
                    </footer>
                </div>
            </div>
        </section>

        <?php require "components/basic/modals.php"; ?>
        <?php require "scripts.php"; ?>
    </body>
</html>

<!-- Se tiver um $page_url definido, chama o seu arquivo actions.js !-->
<?php if(file_exists("pages/$page_url/actions.js")) { ?>
    <script type="text/javascript" charset="utf8" src="pages/<?= $page_url; ?>/actions.js"></script>
<?php } ?>