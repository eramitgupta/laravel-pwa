<?php

namespace EragLaravelPwa\Services;

use Illuminate\Support\Facades\File;
use RuntimeException;

class PWAService
{
    public function HeadTag(): string
    {
        $manifest = asset('/manifest.json');
        $themeColor = e(config('pwa.manifest.theme_color', '#6777ef'));
        $appName = e(config('pwa.manifest.name', config('app.name', 'Laravel PWA')));
        $icon = asset($this->getPrimaryIconPath());
        $installButton = config('pwa.install-button', false);

        $style = self::getInstallButtonStyle($installButton);

        return <<<HTML
        <!-- PWA  -->
        <meta name="theme-color" content="{$themeColor}"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="{$appName}">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" href="{$icon}">
        <link rel="manifest" href="{$manifest}">
        <!-- PWA end -->
        {$style}
        HTML;
    }

    public function RegisterServiceWorkerScript(): string
    {
        // A service worker must come from the page's own origin, so use url() rather than
        // asset() (ASSET_URL may point at a CDN), and scope it to the app's base path.
        $swPath = url('sw.js');
        $scope = rtrim((string) parse_url(url('/'), PHP_URL_PATH), '/').'/';
        $isDebug = config('pwa.debug', false);

        $consoleLog = $isDebug ? 'console.log' : '//';
        $icon = asset($this->getPrimaryIconPath());

        $installButton = config('pwa.install-button', false);

        $installApp = self::getInstallAppHtml($installButton, $icon);
        $installButtonJs = $installButton ? self::installButtonJs() : '';

        $isLivewire = config('pwa.livewire-app', false) ? 'data-navigate-once' : '';

        return <<<HTML
        {$installApp}
        <!-- PWA scripts -->
        <script {$isLivewire}>
            "use strict";
            if ("serviceWorker" in navigator) {
                const registerServiceWorker = () => navigator.serviceWorker.register("{$swPath}", { scope: "{$scope}" }).then(
                    (registration) => {
                        {$consoleLog}("Service worker registration succeeded:");
                    },
                    (error) => {
                        {$consoleLog}("Service worker registration failed", error);
                    }
                );

                if (document.readyState === "complete") {
                    registerServiceWorker();
                } else {
                    window.addEventListener("load", registerServiceWorker, { once: true });
                }
            } else {
                {$consoleLog}("Service workers are not supported.");
            }
            {$installButtonJs}
        </script>
        <!-- PWA scripts -->
        HTML;
    }

    /**
     * Chrome and Edge (Windows, macOS, Android) fire `beforeinstallprompt`, so the
     * button opens the browser's install dialog. Safari has no such event: on
     * iPhone/iPad the user adds the app from the Share menu, and on macOS
     * (Safari 17+) from File > Add to Dock, so those users get a short tip instead.
     */
    private static function installButtonJs(): string
    {
        return <<<'HTML'
            const installPrompt=document.getElementById("install-prompt");const installButton=document.getElementById("install-button");if(installPrompt&&installButton){let deferredPrompt=null;const ua=navigator.userAgent;const displayModes=["standalone","fullscreen","minimal-ui","window-controls-overlay"];const isStandalone=()=>navigator.standalone===true||displayModes.some(mode=>window.matchMedia("(display-mode: "+mode+")").matches);const isIpadOs=()=>/macintosh/i.test(ua)&&navigator.maxTouchPoints>1;const isIosSafari=()=>(/iphone|ipad|ipod/i.test(ua)||isIpadOs())&&/safari/i.test(ua)&&!/chrome|android/i.test(ua);const safariVersion=()=>parseInt((ua.match(/version\/(\d+)/i)||[])[1]||"0",10);const isMacSafari=()=>/macintosh/i.test(ua)&&!isIpadOs()&&/safari/i.test(ua)&&!/chrome|chromium|crios|edg|opr|firefox|android/i.test(ua)&&safariVersion()>=17;const manualInstallMode=()=>isIosSafari()?"ios":isMacSafari()?"macos":null;const show=mode=>{installPrompt.setAttribute("data-mode",mode);installPrompt.style.display="block"};const hide=()=>{installPrompt.style.display="none"};const init=()=>{if(isStandalone()){hide();return}const mode=manualInstallMode();if(mode){show(mode)}};if(document.readyState==="complete"){init()}else{window.addEventListener("load",init,{once:true})}window.addEventListener("beforeinstallprompt",e=>{deferredPrompt=e;show("install")});installButton.addEventListener("click",()=>{if(deferredPrompt){const promptEvent=deferredPrompt;deferredPrompt=null;promptEvent.prompt();promptEvent.userChoice.finally(hide);return}const mode=manualInstallMode();if(mode&&!isStandalone()){show(mode)}});window.addEventListener("appinstalled",()=>{deferredPrompt=null;hide()})}
        HTML;
    }

    private static function getInstallButtonStyle(bool $installButton): string
    {
        if ($installButton) {
            return <<<'HTML'
                <style>
                    .box-icon{position:fixed;bottom:24px;right:24px;z-index:2147483647;max-width:min(320px,calc(100vw - 32px))}.box-icon .circle{cursor:pointer;width:60px;height:60px;background-color:transparent;border:0;border-radius:100%;position:relative;display:flex;align-items:center;justify-content:center;padding:0;transition:transform ease-out .1s,background .2s}.box-icon .circle img{max-width:30px;max-height:30px}.box-icon .circle:after{position:absolute;width:100%;height:100%;border-radius:50%;content:'';top:0;left:0;z-index:-1;animation:shadow-pulse 1s infinite;box-shadow:0 0 0 0 rgb(193 54 1 / 40%)}.box-icon .ios-tip,.box-icon .mac-tip{display:none;margin-top:12px;padding:12px 14px;border-radius:12px;background:#fff;color:#111;font-size:13px;line-height:1.45;box-shadow:0 8px 24px rgba(0,0,0,.12)}.box-icon[data-mode="ios"] .ios-tip,.box-icon[data-mode="macos"] .mac-tip{display:block}@media (max-width:640px){.box-icon{bottom:16px;right:16px;max-width:calc(100vw - 24px)}.box-icon .circle{width:56px;height:56px}}@keyframes shadow-pulse{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 35px rgba(0,0,0,0)}}@keyframes shadow-pulse-big{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 70px rgba(0,0,0,0)}}
                </style>
            HTML;
        }

        return '';
    }

    private static function getInstallAppHtml(bool $installButton, string $icon): string
    {
        if ($installButton) {
            return <<<HTML
                <div id="install-prompt" class="box-icon" style="display: none;" data-mode="install">
                    <button id="install-button" class="circle" type="button" aria-label="Install app">
                        <img src="{$icon}" alt="Install App">
                    </button>
                    <div class="ios-tip">To install on iPhone or iPad, tap Share in Safari, then choose Add to Home Screen.</div>
                    <div class="mac-tip">To install on your Mac, open the File menu in Safari and choose Add to Dock.</div>
                </div>
            HTML;
        }

        return '';
    }

    private function getPrimaryIconPath(): string
    {
        $icons = config('pwa.manifest.icons', []);

        if (is_array($icons) && isset($icons[0]['src']) && is_string($icons[0]['src']) && $icons[0]['src'] !== '') {
            return ltrim($icons[0]['src'], '/');
        }

        return 'logo.png';
    }

    public function createOrUpdate(array $manifest): bool
    {
        if (empty($manifest['icons'])) {
            throw new RuntimeException('Manifest is missing icons. Add at least one 512x512 PNG icon.');
        }

        unset($manifest['start_url']);
        $icons = $manifest['icons'];
        unset($manifest['icons']);

        // `id` and `scope` default to the app root, which matches how browsers already identify
        // the installed app, so existing installs keep working. Values from the config win.
        $arrayMergeManifest = array_merge(['id' => '/', 'scope' => '/'], $manifest, ['start_url' => '/'], ['icons' => $icons]);

        $jsonData = json_encode($arrayMergeManifest, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            throw new RuntimeException('Failed to encode manifest array to JSON. Aborting operation.');
        }

        $jsonData = str_replace('\/', '/', $jsonData);

        $filePath = public_path('manifest.json');
        if (! File::isWritable(public_path())) {
            throw new RuntimeException('Public directory is not writable. Check file permissions.');
        }

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        File::put($filePath, $jsonData);

        return true;
    }

    public function update(array $manifestData): bool
    {
        try {
            if ($this->createOrUpdate($manifestData)) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
