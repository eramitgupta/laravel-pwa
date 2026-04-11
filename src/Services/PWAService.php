<?php

namespace EragLaravelPwa\Services;

use Illuminate\Support\Facades\File;
use RuntimeException;

class PWAService
{
    public function HeadTag(): string
    {
        $manifest = asset('/manifest.json');
        $themeColor = config('pwa.manifest.theme_color', '#6777ef');
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
        $swPath = asset('/sw.js');
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
                navigator.serviceWorker.register("{$swPath}", { scope: "/" }).then(
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
            const installPrompt=document.getElementById("install-prompt");const installButton=document.getElementById("install-button");if(installPrompt&&installButton){let deferredPrompt;const isStandalone=()=>window.matchMedia("(display-mode: standalone)").matches||window.navigator.standalone===true;const isIos=()=>/iphone|ipad|ipod/i.test(window.navigator.userAgent);const isSafari=()=>{const ua=window.navigator.userAgent.toLowerCase();return ua.includes("safari")&&!ua.includes("chrome")&&!ua.includes("android")};const showInstallPromotion=()=>{installPrompt.style.display="block";installPrompt.setAttribute("data-mode","install")};const showIosInstructions=()=>{installPrompt.style.display="block";installPrompt.setAttribute("data-mode","ios")};window.addEventListener("load",()=>{if(isStandalone()){installPrompt.style.display="none";return}if(isIos()&&isSafari()){showIosInstructions()}});window.addEventListener("beforeinstallprompt",e=>{deferredPrompt=e;showInstallPromotion()});installButton.addEventListener("click",()=>{if(deferredPrompt&&typeof deferredPrompt.prompt==="function"){deferredPrompt.prompt();deferredPrompt.userChoice.finally(()=>{deferredPrompt=null})}else if(isIos()&&isSafari()&&!isStandalone()){showIosInstructions()}});window.addEventListener("appinstalled",()=>{installPrompt.style.display="none"})}
        HTML;
    }

    private static function getInstallButtonStyle(bool $installButton): string
    {
        if ($installButton) {
            return <<<'HTML'
                <style>
                    .box-icon{position:fixed;bottom:24px;right:24px;z-index:2147483647;max-width:min(320px,calc(100vw - 32px))}.box-icon .circle{cursor:pointer;width:60px;height:60px;background-color:transparent;border:0;border-radius:100%;position:relative;display:flex;align-items:center;justify-content:center;padding:0;transition:transform ease-out .1s,background .2s}.box-icon .circle img{max-width:30px;max-height:30px}.box-icon .circle:after{position:absolute;width:100%;height:100%;border-radius:50%;content:'';top:0;left:0;z-index:-1;animation:shadow-pulse 1s infinite;box-shadow:0 0 0 0 rgb(193 54 1 / 40%)}.box-icon .ios-tip{display:none;margin-top:12px;padding:12px 14px;border-radius:12px;background:#fff;color:#111;font-size:13px;line-height:1.45;box-shadow:0 8px 24px rgba(0,0,0,.12)}.box-icon[data-mode="ios"] .ios-tip{display:block}.box-icon[data-mode="install"] .ios-tip{display:none}@media (max-width:640px){.box-icon{bottom:16px;right:16px;max-width:calc(100vw - 24px)}.box-icon .circle{width:56px;height:56px}}@keyframes shadow-pulse{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 35px rgba(0,0,0,0)}}@keyframes shadow-pulse-big{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 70px rgba(0,0,0,0)}}
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
        unset($manifest['start_url']);
        $icons = $manifest['icons'];
        unset($manifest['icons']);

        $arrayMergeManifest = array_merge($manifest, ['start_url' => '/'], ['icons' => $icons]);

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
