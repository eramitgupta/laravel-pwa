<?php

namespace EragLaravelPwa\Services;

class PWAService
{
    public function HeadTag(): string
    {
        $manifest = asset('/manifest.json');
        $themeColor = config('pwa.manifest.theme_color', '#6777ef');
        $icon = asset(config('pwa.manifest.icons.src', 'logo.png'));
        $installButton = config('pwa.install-button', false);

        $style = self::getInstallButtonStyle($installButton);

        return <<<HTML
        <!-- PWA  -->
        <meta name="theme-color" content="{$themeColor}"/>
        <link rel="apple-touch-icon" href="{$icon}">
        <link rel="manifest" href="{$manifest}">
        <!-- PWA end -->
        {$style}
        HTML;
    }

    public function RegisterServiceWorkerScript(): string
    {
        $swPath = asset('/sw.js');
        $isDebug = config('pwa.debug', false);
        $consoleLog = $isDebug ? 'console.log' : '//';
        $icon = asset(config('pwa.manifest.icons.src', 'logo.png'));
        $installButton = config('pwa.install-button', false);

        $installApp = self::getInstallAppHtml($installButton, $icon);
        $installButtonJs = $installButton ? self::installButtonJs() : '';

        return <<<HTML
        {$installApp}
        <!-- PWA scripts -->
        <script src="{$swPath}"></script>
        <script>
            if ("serviceWorker" in navigator) {
                navigator.serviceWorker.register("/sw.js").then(
                    (registration) => {
                        {$consoleLog}("Service worker registration succeeded:");
                    },
                    (error) => {
                        {$consoleLog}("Service worker registration failed", error);
                    }
                );
            } else {
                {$consoleLog}("Service workers are not supported.");
            }
            {$installButtonJs}
        </script>
        <!-- PWA scripts -->
        HTML;
    }

    private static function installButtonJs(): string
    {
        return <<<'HTML'
            let deferredPrompt;function showInstallPromotion(){document.getElementById("install-prompt").style.display="block"}window.addEventListener("load",(()=>{if(window.matchMedia("(display-mode: standalone)").matches){document.getElementById("install-prompt").style.display="none"}})),window.addEventListener("beforeinstallprompt",(e=>{e.preventDefault(),deferredPrompt=e,showInstallPromotion();document.getElementById("install-button").addEventListener("click",(()=>{deferredPrompt.prompt(),deferredPrompt.userChoice.then((e=>{deferredPrompt=null}))}))})),window.addEventListener("appinstalled",(()=>{document.getElementById("install-prompt").style.display="none"}));
        HTML;
    }

    private static function getInstallButtonStyle(bool $installButton): string
    {
        if ($installButton) {
            return <<<'HTML'
                <style>
                    .box-icon{position:fixed;bottom:100px;right:100px}.box-icon .circle{cursor:pointer;width:60px;height:60px;background-color:rgba(255,150,35,0.2);border-radius:100%;position:absolute;top:-10px;left:-10px;transition:transform ease-out 0.1s,background 0.2s}.box-icon .circle:after{position:absolute;width:100%;height:100%;border-radius:50%;content:'';top:0;left:0;z-index:-1;animation:shadow-pulse 1s infinite;box-shadow:0 0 0 0 rgb(193 54 1 / 40%)}@keyframes shadow-pulse{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 35px rgba(0,0,0,0)}}@keyframes shadow-pulse-big{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 70px rgba(0,0,0,0)}}
                </style>
            HTML;
        }

        return '';
    }

    private static function getInstallAppHtml(bool $installButton, string $icon): string
    {
        if ($installButton) {
            return <<<HTML
                <div id="install-prompt" class="box-icon" style="display: none;">
                    <span id="install-button" class="circle">
                        <img src="{$icon}" alt="Install App">
                    </span>
                </div>
            HTML;
        }

        return '';
    }
}
