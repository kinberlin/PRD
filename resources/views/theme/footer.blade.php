
@if(session('error'))
    <div id="myModal" class="modals">
        <div class="modal-contents">
            <span class="closes" onclick="closeModal()">&times;</span>
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif
<footer class="content-footer footer bg-footer-theme">
    <div
        class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>, Cadyst Group - WorkWave <a href="https://themeselection.com/"
                target="_blank" class="footer-link fw-medium">ThemeSelection</a>
        </div>
    </div>
</footer>