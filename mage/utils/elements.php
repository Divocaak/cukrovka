<?php
function footer($gameName)
{
    echo '<footer class="bg-light text-center text-lg-start">
    <div class="container p-4">
        <div class="row">
            footer content here
        </div>
    </div>

    <div class="text-center p-3 text-light bg-secondary">
        <div class="row pb-3">
            <div class="col-3"></div>
            <div class="col-3">
                <p>Divočák</p>
                <a class="text-light" target="_blank" href="https://www.instagram.com/divokyvojta/"><i class="bi bi-instagram"></i></a>
                <a class="text-light" target="_blank" href="https://www.facebook.com/divokyv/"><i class="bi bi-facebook"></i></a>
                <a class="text-light" target="_blank" href="https://twitter.com/DivokyVojtech"><i class="bi bi-twitter"></i></a>
            </div>
            <div class="col-3">
                <p>Tesák</p>
                <a class="text-light" target="_blank" href="https://www.instagram.com/pan_tesar/"><i class="bi bi-instagram"></i></a>
                <a class="text-light" target="_blank" href="https://www.facebook.com/jaackob.tesar"><i class="bi bi-facebook"></i></a>
                <a class="text-light" target="_blank" href="https://twitter.com/StarGonCZ"><i class="bi bi-twitter"></i></a>
            </div>
            <div class="col-3"></div>
        </div>
        <p>' . $gameName . ' © 2021 Copyright: Divočák, Tesák</p>
    </div>
</footer>';
}
?>