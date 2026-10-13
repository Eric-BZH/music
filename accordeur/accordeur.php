<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>BombardeLab Accordeur</title>
    <meta name="theme-color" content="#8b5cf6">
    <meta name="description" content="Accordeur BombardeLab : bombarde, cornemuse, tempéraments Bagad et Pipe Band">
    <link rel="manifest" href="manifest.webmanifest">
    <link rel="icon" type="image/png" sizes="192x192" href="icons/icon-192.png">
    <link rel="apple-touch-icon" href="icons/apple-touch-icon.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Accordeur">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap');

        /* --- 0. GESTION DES NAVBARS --- */
        /* Navbar supprimée du code HTML, suppression des styles liés */

        /* --- 1. ANIMATIONS D'ARRIÈRE-PLAN --- */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        /* Bande pleine sous la barre d'état (heure, batterie) quand l'appli est installée :
           cache les halos flous du fond qui passeraient derrière l'heure.
           Les halos violet et vert sont placés au milieu de l'écran pour ne pas flouter le haut. */
        .status-bar-bg { position: fixed; top: 0; left: 0; right: 0; height: env(safe-area-inset-top, 0px); background-color: var(--bg-color); z-index: 5000; pointer-events: none; transition: background-color 0.4s ease; }
        .blob-bg { position: fixed; inset: 0; width: 100%; height: 100%; overflow: hidden; z-index: -10; pointer-events: none; }
        .blob { position: absolute; border-radius: 50%; mix-blend-mode: multiply; filter: blur(40px); opacity: 0.4; animation: blob 10s infinite; }
        .blob-1 { top: 35vh; left: -1rem; width: 18rem; height: 18rem; background-color: rgb(196, 181, 253); }
        .blob-2 { top: 35vh; right: -1rem; width: 18rem; height: 18rem; background-color: rgb(110, 231, 183); animation-delay: 2s; }
        .blob-3 { bottom: -2rem; left: 5rem; width: 18rem; height: 18rem; background-color: rgb(253, 186, 116); animation-delay: 4s; }
        [data-theme="dark"] .blob { mix-blend-mode: screen; opacity: 0.15; }

        /* --- 2. COULEURS & VARIABLES --- */
        :root {
            --bg-color: #0f172a; 
            --panel-bg: rgba(30, 41, 59, 0.65);
            --text-main: #f1f5f9; --text-muted: #94a3b8;
            --accent: #8b5cf6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
            --needle-color: #10b981; --gauge-color: #8b5cf6; --marker-major: #475569; --marker-minor: #334155;
            --graph-line: #8b5cf6; --panel-border: rgba(255,255,255,0.1);
            --panel-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); --graph-grid: rgba(255,255,255,0.05);
            --input-bg: rgba(2, 6, 23, 0.6);
            --icon-color: rgba(255,255,255,0.6); --strobe-color: 255, 255, 255;
            --btn-primary-bg: linear-gradient(135deg, #8b5cf6, #d946ef); --btn-primary-text: #ffffff;
            --color-flat-blue: #3b82f6; --chip-default-bg: #0f172a; --chip-default-border: #334155;
            --chip-active-bg: #8b5cf6; --chip-active-text: #ffffff; --stat-text: #94a3b8;
            --stat-tonic: #8b5cf6; --stat-line-sharp: #ef4444; --stat-line-flat: #3b82f6;
            --stat-line-perfect: #10b981; --metro-halo-color: 255, 255, 255;
        }
        [data-theme="dark"] {
            --bg-color: #0f172a; 
            --panel-bg: rgba(30, 41, 59, 0.65);
            --text-main: #f1f5f9; --text-muted: #94a3b8;
            --accent: #8b5cf6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
            --needle-color: #10b981; --gauge-color: #8b5cf6; --marker-major: #475569;
            --marker-minor: #334155; --graph-line: #8b5cf6; --panel-border: rgba(255,255,255,0.1);
            --panel-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); --graph-grid: rgba(255,255,255,0.05);
            --input-bg: rgba(2, 6, 23, 0.6);
            --icon-color: rgba(255,255,255,0.6); --strobe-color: 255, 255, 255;
            --btn-primary-bg: linear-gradient(135deg, #8b5cf6, #d946ef); --btn-primary-text: #ffffff;
            --color-flat-blue: #3b82f6; --chip-default-bg: #0f172a; --chip-default-border: #334155;
            --chip-active-bg: #8b5cf6; --chip-active-text: #ffffff; --stat-text: #94a3b8;
            --stat-tonic: #8b5cf6; --stat-line-sharp: #ef4444; --stat-line-flat: #3b82f6;
            --stat-line-perfect: #10b981; --metro-halo-color: 255, 255, 255;
        }
        [data-theme="light"] {
            --bg-color: #fdfbf7; 
            --panel-bg: rgba(255, 255, 255, 0.45);
            --text-main: #334155; --text-muted: #94a3b8;
            --accent: #8b5cf6; --success: #10b981; --warning: #f97316; --danger: #ef4444;
            --gauge-color: #8b5cf6; --needle-color: #10b981; --marker-major: #e2e8f0;
            --marker-minor: #f1f5f9; --graph-line: #8b5cf6; --panel-border: rgba(255, 255, 255, 0.4);
            --panel-shadow: 0 20px 40px -5px rgba(139, 92, 246, 0.15), 0 0 15px rgba(0,0,0,0.03);
            --graph-grid: #f1f5f9; 
            --input-bg: rgba(248, 250, 252, 0.6);
            --icon-color: #64748b; --strobe-color: 51, 65, 85;
            --btn-primary-bg: linear-gradient(135deg, #8b5cf6, #d946ef); --btn-primary-text: #ffffff;
            --color-flat-blue: #3b82f6; --chip-default-bg: #f1f5f9; --chip-default-border: transparent;
            --chip-active-bg: #8b5cf6; --chip-active-text: #ffffff; --stat-text: #94a3b8;
            --stat-tonic: #8b5cf6; --stat-line-sharp: #ef4444; --stat-line-flat: #3b82f6;
            --stat-line-perfect: #10b981; --metro-halo-color: 139, 92, 246;
        }

        /* Navbar Content Supprimé - styles gardés au cas où mais inutilisés */
        .nav-content { width: 100%; max-width: 1200px; padding: 0 32px; display: flex; justify-content: space-between; align-items: center; }
        
        * { box-sizing: border-box; user-select: none; -webkit-tap-highlight-color: transparent; }
        
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: var(--bg-color); 
            color: var(--text-main); 
            margin: 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            height: 100vh; 
            padding-top: max(20px, env(safe-area-inset-top));
            padding-bottom: max(8px, calc(env(safe-area-inset-bottom) - 16px));
            padding-left: max(20px, env(safe-area-inset-left));
            padding-right: max(20px, env(safe-area-inset-right));
            overflow: hidden; 
            transition: background-color 0.4s ease, color 0.3s ease; 
            touch-action: manipulation; 
        }

        #metro-overlay { position: fixed; inset: 0; background: radial-gradient(circle, transparent 60%, rgba(var(--metro-halo-color), 0.3) 100%); box-shadow: inset 0 0 150px rgba(var(--metro-halo-color), 0.5); pointer-events: none; z-index: 50; opacity: 0; transition: opacity 0.05s; }
        body.metro-flash #metro-overlay { opacity: 1; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .tuner-container, .controls-container, .stats-container, .player-container, .side-panel, .modal-content {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .tuner-container { position: relative; width: 100%; max-width: 400px; height: 200px; flex-shrink: 0; background-color: var(--panel-bg); border-radius: 32px; box-shadow: var(--panel-shadow); display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 24px; margin-bottom: 24px; overflow: hidden; cursor: pointer; border: 1px solid var(--panel-border); transition: transform 0.2s ease, box-shadow 0.3s ease; }
        .tuner-container:active { transform: scale(0.995); }
        .header-icon-btn { position: absolute; width: 40px; height: 40px; z-index: 20; cursor: pointer; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: var(--input-bg); border: none; color: var(--text-muted); transition: all 0.2s; padding: 0; margin: 0; }
        .header-icon-btn:hover { background: var(--chip-active-bg); color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3); }
        .header-icon-btn svg { width: 35px; height: 35px; stroke-width: 1.5; display: block; }
        .settings-btn { top: 15px; right: 20px; }
        .help-btn { top: 15px; right: 70px; }
        .metro-btn { top: 65px; right: 20px; }
        .metro-btn svg path { fill: var(--text-muted); transition: fill 0.2s; } .metro-btn:hover svg path { fill: white; }
        #strobe-canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none; opacity: 0.6; mask-image: radial-gradient(circle at 50% 100%, transparent 20%, black 60%); -webkit-mask-image: radial-gradient(circle at 50% 100%, transparent 20%, black 60%); transition: opacity 0.5s ease; }
        .gauge-elements { transition: opacity 0.3s ease; }
        .gauge-arc { position: absolute; top: 35px; left: 50%; transform: translateX(-50%); width: 280px; height: 140px; border-radius: 140px 140px 0 0; border: 8px solid var(--gauge-color); border-bottom: none; z-index: 2; opacity: 0.15; transition: border-color 0.3s ease, opacity 0.3s ease; }
        .center-marker { position: absolute; top: 27px; left: 50%; transform: translateX(-50%); width: 6px; height: 18px; background: var(--success); border-radius: 99px; z-index: 3; box-shadow: 0 0 10px var(--success); transition: opacity 0.3s ease; }
        .guide-marker-wrapper { position: absolute; top: 175px; left: 50%; width: 0; height: 0; z-index: 4; display: flex; justify-content: center; transition: opacity 0.3s ease; }
        .guide-tick { position: absolute; bottom: 140px; width: 4px; height: 10px; background: currentColor; border-radius: 99px; opacity: 0.6; }
        .needle-wrapper { position: absolute; top: 175px; left: 50%; width: 0; height: 0; z-index: 10; transition: opacity 0.3s ease; }
        .needle { position: absolute; bottom: 0; left: -4px; width: 8px; height: 145px; background: var(--needle-color); border-radius: 99px; transform-origin: bottom center; box-shadow: 0 -4px 15px rgba(0,0,0,0.1); }
        .needle-pivot { position: absolute; top: 169px; left: 50%; transform: translateX(-50%); width: 20px; height: 20px; background: var(--text-main); border-radius: 50%; z-index: 11; border: 4px solid var(--panel-bg); box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: opacity 0.3s ease; }
        .tuner-container.strobe-mode .gauge-arc, .tuner-container.strobe-mode .center-marker, .tuner-container.strobe-mode .guide-marker-wrapper, .tuner-container.strobe-mode .needle-wrapper, .tuner-container.strobe-mode .needle-pivot { opacity: 0; }
        .info-display { z-index: 12; text-align: center; margin-top: 105px; }
        .note-name { font-size: 4rem; font-weight: 800; line-height: 0.9; letter-spacing: -1px; background: linear-gradient(135deg, var(--text-main) 0%, var(--text-muted) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; color: var(--text-main); min-height: 4rem; }
        .note-name[style*="color"] { background: none; -webkit-text-fill-color: initial; }
        .note-octave { font-size: 2rem; font-weight: 600; opacity: 0.6; -webkit-text-fill-color: initial; color: var(--text-muted); }
        .hz-offset-display { font-family: 'Outfit', monospace; font-size: 1.1rem; font-weight: 700; margin-top: 8px; }
        .freq-display { font-family: 'Outfit', sans-serif; color: var(--text-muted); font-size: 0.85rem; margin-top: 4px; font-weight: 500; }
        .tuner-header-left { position: absolute; top: 15px; left: 24px; z-index: 25; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .logo-img-header { height: 54px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); }
        .controls-container { width: 100%; max-width: 400px; background: var(--panel-bg); border-radius: 24px; padding: 12px 16px; margin-bottom: 24px; display: flex; flex-direction: column; gap: 8px; box-shadow: var(--panel-shadow); border: 1px solid var(--panel-border); flex-shrink: 0; }
        .player-container { width: 100%; max-width: 400px; background: var(--panel-bg); border-radius: 24px; padding: 12px 16px; margin-bottom: 24px; display: none; flex-direction: column; gap: 8px; box-shadow: var(--panel-shadow); border: 2px solid var(--accent); flex-shrink: 0; }
        .player-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; height: 32px; }
        .player-waveform { flex-grow: 1; height: 36px; background: var(--input-bg); border-radius: 8px; position: relative; cursor: pointer; border: 1px solid var(--panel-border); }
        #waveform-canvas { width: 100%; height: 100%; display: block; border-radius: 8px; }
        .crop-handle { position: absolute; top: -4px; bottom: -4px; width: 14px; background: var(--accent); cursor: ew-resize; z-index: 20; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); transform: translateX(-50%); }
        .crop-handle::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 2px; height: 16px; background: white; border-radius: 2px; }
        .crop-overlay { position: absolute; top: 0; bottom: 0; background: rgba(0, 0, 0, 0.6); pointer-events: none; z-index: 10; }
        #crop-handle-start { left: 0; } 
        .playhead-marker { position: absolute; top: 0; bottom: 0; width: 2px; background: #ef4444; z-index: 15; pointer-events: none; }
        .control-row { display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap; }
        .hz-control { display: flex; align-items: center; background: var(--input-bg); border-radius: 16px; padding: 4px; flex-shrink: 0; }
        .hz-value { font-family: 'Outfit', monospace; font-weight: 600; font-size: 1rem; padding: 0 8px; min-width: 60px; text-align: center; cursor: pointer; color: var(--text-main); }
        button { font-family: 'Outfit', sans-serif; border: none; padding: 10px 16px; border-radius: 14px; font-size: 0.9rem; cursor: pointer; transition: all 0.2s ease; font-weight: 600; touch-action: manipulation; }
        button:active { transform: scale(0.96); }
        button.icon-btn { width: 32px; height: 32px; border-radius: 12px; background: white; color: var(--text-main); box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; padding: 0; }
        button.icon-btn svg { display: block; } 
        button.icon-btn:hover { background: var(--accent); color: white; }
        button.rec-btn { color: var(--danger); background: rgba(239, 68, 68, 0.15); }
        button.rec-btn:hover { background: rgba(239, 68, 68, 0.25); color: var(--danger); }
        button.rec-btn.recording { background: var(--danger); color: white; animation: pulseRed 1.5s infinite; }
        @keyframes pulseRed { 0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); } 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }
        [data-theme="dark"] button.icon-btn { background: #334155; }
        button.primary { background: var(--btn-primary-bg); color: var(--btn-primary-text); box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3); }
        button.primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4); }
        #btn-calibrate { padding: 8px 16px; border-radius: 14px; flex-shrink: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background: rgba(139, 92, 246, 0.15); color: var(--accent); box-shadow: none; }
        #btn-calibrate:hover { background: rgba(139, 92, 246, 0.25); box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1); }
        button.toggle-btn { background: var(--chip-default-bg); color: var(--text-muted); border-radius: 14px; padding: 8px 16px; flex-shrink: 0; }
        button.active-toggle { background: var(--chip-active-bg); color: var(--chip-active-text); }
        #calibration-msg { text-align: center; font-size: 0.85rem; height: 18px; color: var(--text-main); font-weight: 700; margin-top: 0; }
        #temperament-info { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-align: center; margin-top: -4px; }
        .stats-container { width: 100%; max-width: 400px; flex-grow: 1; min-height: 0; background: var(--panel-bg); border-radius: 32px; padding: 24px; display: flex; flex-direction: column; box-shadow: var(--panel-shadow); border: 1px solid var(--panel-border); position: relative; }
        .stats-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--marker-minor); flex-shrink: 0; }
        .stats-title-group span { font-size: 1.1rem; font-weight: 800; color: var(--text-main); }
        .stats-controls { display: flex; gap: 8px; }
        #btn-stats-mode { background: var(--input-bg); border: 1px solid transparent; font-size: 0.75rem; padding: 6px 12px; border-radius: 99px; color: var(--text-muted); font-weight: 700; }
        #btn-stats-mode.active-auto { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        #btn-reset-stats { background: rgba(59, 130, 246, 0.1); color: var(--color-flat-blue); border: 1px solid var(--color-flat-blue); padding: 6px 12px; font-size: 0.8rem; border-radius: 99px; }
        #btn-reset-stats:hover { background: var(--color-flat-blue); color: white; }
        #btn-pause-stats { background: var(--input-bg); color: var(--text-main); border: 1px solid var(--panel-border); padding: 6px 12px; font-size: 0.8rem; border-radius: 99px; display: flex; align-items: center; gap: 4px; }
        #btn-pause-stats.paused { background: var(--warning); color: #000; border-color: var(--warning); }
        #stats-graph-container { width: 100%; height: 100%; min-height: 0; position: relative; flex-grow: 1; }
        .stat-popup { background: var(--panel-bg); border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); padding: 0; border: none; overflow: hidden; width: 140px; display: none; position: absolute; z-index: 1000; animation: fadeIn 0.2s ease; }
        .stat-popup.visible { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translate(-50%, 45%); } to { opacity: 1; transform: translate(-50%, 50%); } }
        .stat-popup .stat-card { border: none; background: transparent; height: auto; padding: 12px; }
        .stat-popup .stat-note { font-size: 1.6rem; font-weight: 800; margin-bottom: 4px; }
        #start-overlay { position: fixed; inset: 0; z-index: 100; display: flex; justify-content: center; align-items: center; flex-direction: column; background: rgba(255, 255, 255, 0.3); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
        [data-theme="dark"] #start-overlay { background: rgba(15, 23, 42, 0.6); }
        #start-btn { background: linear-gradient(135deg, #10b981, #059669); color: white; font-size: 1.2rem; padding: 18px 48px; border-radius: 24px; font-weight: 800; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4); border: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        #start-btn:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 20px 40px rgba(16, 185, 129, 0.5); }
        .footer-signature { margin-top: auto; padding: 8px 20px 0; font-size: 0.85rem; color: var(--text-muted); text-align: center; font-weight: 600; opacity: 0.7; flex-shrink: 0; }
        .side-panel { position: fixed; top: 0; right: -360px; width: 340px; height: 100%; background: var(--panel-bg); border-top-left-radius: 32px; border-bottom-left-radius: 32px; box-shadow: -10px 0 40px rgba(0,0,0,0.1); z-index: 2000; transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1); padding: 32px; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; }
        .side-panel.open { right: 0; }
        .panel-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.2); z-index: 1999; opacity: 0; pointer-events: none; transition: opacity 0.3s; backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
        .panel-overlay.open { opacity: 1; pointer-events: auto; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; color: var(--text-main); font-size: 1.5rem; font-weight: 800; border-bottom: none; margin-bottom: 20px; }
        .close-panel-btn { background: transparent; border: none; font-size: 2rem; color: var(--text-muted); cursor: pointer; }
        .setting-item { display: flex; flex-direction: column; gap: 8px; }
        .app-version { margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--panel-border); text-align: center; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); }
        .setting-label { font-weight: 600; margin-bottom: 8px; color: var(--text-main); display: flex; justify-content: space-between; align-items: center; }
        .reset-param-btn { background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.1rem; padding: 0 5px; transition: color 0.2s; line-height: 1; }
        .reset-param-btn:hover { color: var(--accent); }
        .setting-input-num { width: 80px; background: var(--input-bg); border: 1px solid var(--panel-border); border-radius: 12px; padding: 8px; color: var(--text-main); text-align: center; font-family: monospace; font-weight: bold; }
        .theme-switcher, .option-row { display: flex; background: var(--input-bg); padding: 4px; border: none; border-radius: 99px; }
        .theme-opt { flex: 1; padding: 8px 12px; font-weight: 600; text-align: center; cursor: pointer; font-size: 0.85rem; border-radius: 99px; transition: background 0.2s; color: var(--text-main); }
        .theme-opt.active { background: var(--chip-active-bg); color: var(--chip-active-text); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .custom-slider-wrapper { background: var(--input-bg); border-radius: 99px; height: 12px; border: none; position: relative; width: 100%; margin-top: 5px; }
        .gate-level-bar { position: absolute; top: 0; left: 0; height: 100%; width: 0%; background-color: var(--text-muted); transition: width 0.05s linear, background-color 0.1s; z-index: 0; opacity: 0.6; border-radius: 99px; }
        input[type=range] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; margin: 0; padding: 0; opacity: 1; z-index: 2; background: transparent; appearance: none; -webkit-appearance: none; cursor: pointer; }
        input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 20px; height: 20px; background: var(--accent); border: 2px solid white; border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
        .toggle-row { display: flex; justify-content: space-between; align-items: center; background: var(--input-bg); padding: 12px; border-radius: 16px; color: var(--text-main); font-weight: 600; }
        .toggle-switch { position: relative; width: 44px; height: 24px; background: #cbd5e1; border-radius: 99px; cursor: pointer; transition: background 0.2s; }
        .toggle-switch.on { background: var(--success); }
        .toggle-thumb { position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
        .toggle-switch.on .toggle-thumb { transform: translateX(20px); }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.2); z-index: 3000; display: none; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
        .modal-content { background: #ffffff; border-radius: 32px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); padding: 32px; width: 100%; max-width: 350px; text-align: center; animation: modalFadeIn 0.3s ease-out; position: relative; }
        [data-theme="dark"] .modal-content { background: #1e293b; }
        #metronome-modal .modal-content { background: rgba(255, 255, 255, 0.85); }
        [data-theme="dark"] #metronome-modal .modal-content { background: rgba(30, 41, 59, 0.9); }
        .key-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 20px; }
        .key-grid button { background: var(--input-bg); font-size: 1rem; font-weight: 600; padding: 12px 0; border-radius: 12px; }
        .key-grid button:hover { background: var(--accent); color: white; }
        .temp-grid { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        .temp-grid button { background: var(--input-bg); padding: 16px; font-size: 1.1rem; border-radius: 16px; }
        .temp-grid button:hover { background: var(--accent); color: white; }
        .pitch-input-wrapper { margin: 20px 0; }
        #pitch-input { width: 100%; background: var(--input-bg); color: var(--text-main); border: 1px solid transparent; border-radius: 16px; padding: 12px; font-size: 1.5rem; text-align: center; font-family: monospace; font-weight: bold; }
        #pitch-input:focus { outline: 2px solid var(--accent); background: var(--panel-bg); }
        .metro-controls { display: flex; flex-direction: column; gap: 16px; margin-top: 10px; }
        .metro-bpm-row { display: flex; justify-content: center; align-items: center; gap: 20px; }
        .bpm-display { font-size: 3rem; font-weight: 800; font-variant-numeric: tabular-nums; cursor: pointer; transition: color 0.2s; }
        .bpm-display:hover { color: var(--accent); }
        .bpm-input-edit { font-size: 2.5rem; font-weight: 800; width: 100px; text-align: center; background: var(--input-bg); border: 2px solid var(--accent); border-radius: 12px; color: var(--text-main); }
        .metro-btn-circle { width: 64px; height: 64px; border-radius: 50%; background: var(--accent); color: white; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4); padding: 0; }
        .metro-btn-circle svg { display: block; }
        .metro-btn-circle.playing { background: var(--danger); box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); }
        .metro-options { display: flex; justify-content: space-around; background: var(--input-bg); padding: 8px; border-radius: 16px; }
        .metro-opt-btn { background: transparent; color: var(--text-muted); padding: 8px; flex: 1; font-size: 0.9rem; }
        .metro-opt-btn.active { background: var(--panel-bg); color: var(--accent); box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-radius: 10px; font-weight: 700; }
        .metro-tap-btn { background: var(--input-bg); color: var(--text-main); border: 2px solid var(--panel-border); font-weight: 700; padding: 12px 24px; border-radius: 16px; font-size: 1rem; width: 100%; transition: all 0.1s; }
        .metro-tap-btn:active { background: var(--accent); color: white; transform: scale(0.98); }
        .side-panel { background: rgba(255, 255, 255, 0.9) !important; }
        [data-theme="dark"] .side-panel { background: rgba(30, 41, 59, 0.95) !important; }

        @media (min-width: 768px) {
            body { 
                /* Padding réduit maintenant que la navbar est partie */
                min-height: 100vh; overflow-y: auto; 
            }
            /* On s'assure que le logo interne est visible puisque la navbar n'est plus là */
            .tuner-header-left { display: flex !important; }
        }

    </style>
</head>
<body data-theme="light">
    <div class="status-bar-bg"></div>
    <div class="blob-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div id="metro-overlay"></div>

    <div id="start-overlay">
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom: 40px;">
            <img src="logo-anim.gif" class="animate-float" style="width: 80px; height: auto; margin-bottom: 15px;" alt="Logo BombardeLab">
            <h1 style="font-weight: 800; color: var(--text-main); font-size: 3.5rem; letter-spacing: -2px; line-height: 1; margin:0; text-align: center;">
                Bombarde<span style="color:var(--accent);">Lab</span>
            </h1>
            <h2 data-i18n="tuner_subtitle" style="font-weight: 600; color: var(--text-muted); font-size: 1.5rem; margin-top: 10px; letter-spacing: 1px; text-transform: uppercase;">Accordeur</h2>
        </div>

        <button id="start-btn" data-i18n="btn_ready">Ready ? Accordez !</button>
        <p id="start-error" style="color: var(--danger); margin-top: 10px; min-height: 20px; text-align: center; max-width: 80%; font-weight: 600; font-size: 0.9rem;"></p>
        <p style="color: var(--text-muted); margin-top: 10px; font-size: 0.9rem; font-weight: 500;" data-i18n="mic_required">Accès micro requis</p>
    </div>

    <div id="panel-overlay" class="panel-overlay"></div>

    <div id="settings-panel" class="side-panel">
        <div class="panel-header"><span data-i18n="settings_title">Paramètres</span><button class="close-panel-btn">×</button></div>
        
        <div class="setting-item"><div class="setting-label" data-i18n="theme_label">Thème</div><div class="theme-switcher" id="theme-switcher-main"><div class="theme-opt" data-val="dark" data-i18n="theme_dark">Nuit</div><div class="theme-opt" data-val="light" data-i18n="theme_light">Jour</div><div class="theme-opt active" data-val="system" data-i18n="theme_auto">Auto</div></div></div>
        
        <div class="setting-item"><div class="setting-label"><span data-i18n="default_pitch_label">Diapason par défaut (Hz)</span><input type="number" id="default-pitch-input" class="setting-input-num" step="0.01" min="400" max="480" value="446.0"></div></div>
        
        <div class="setting-item">
            <div class="setting-label">Tempérament actuel</div>
            <div class="theme-switcher" id="active-temp-switcher">
                <div class="theme-opt" data-val="equal">TE</div>
                <div class="theme-opt" data-val="just" data-i18n="temp_short_just">Juste</div>
                <div class="theme-opt active" data-val="bagad">Bagad</div>
                <div class="theme-opt" data-val="pipeband">Pipe</div>
            </div>
        </div>

        <div class="setting-item"><div class="setting-label" data-i18n="default_temp_label">Tempérament par défaut</div><div class="theme-switcher" id="default-temp-switcher"><div class="theme-opt" data-val="equal">TE</div><div class="theme-opt" data-val="just" data-i18n="temp_short_just">Juste</div><div class="theme-opt active" data-val="bagad">Bagad</div></div></div>
        
        <div class="setting-item"><div class="setting-label" data-i18n="default_notation_label">Notation par défaut</div><div class="option-row" id="default-lang-switcher"><div class="theme-opt active" data-val="fr">FR</div><div class="theme-opt" data-val="en">EN</div></div></div>
        <div class="setting-item"><div class="setting-label"><span data-i18n="gate_label">Seuil de détection (Gate)</span><div><span id="gate-val" style="color:var(--accent); font-weight:bold;">33%</span><button class="reset-param-btn" id="reset-gate" title="Défaut">↺</button></div></div><div class="custom-slider-wrapper"><div id="gate-level-bar" class="gate-level-bar"></div><input type="range" id="gate-slider" min="0.001" max="0.03" step="0.001" value="0.01"></div></div>
        <div class="setting-item"><div class="setting-label"><span data-i18n="reset_delay_label">Délai bouton Réinitialiser</span><div><span id="reset-delay-val">0s</span><button class="reset-param-btn" id="reset-reset-delay" title="Défaut">↺</button></div></div><div class="custom-slider-wrapper"><input type="range" id="reset-delay-slider" min="0" max="3" step="1" value="0"></div></div>
        <div class="setting-item"><div class="toggle-row"><span data-i18n="strobe_label">Afficher Stroboscope</span><div class="toggle-switch on" id="toggle-strobe"><div class="toggle-thumb"></div></div></div></div>
        <div class="setting-item" id="strobe-smoothing-container"><div class="setting-label"><span data-i18n="strobe_smooth_label">Lissage Stroboscope</span><div><span id="strobe-smooth-val" style="font-weight:bold; font-family:monospace; color:var(--text-muted);">83 ms</span><button class="reset-param-btn" id="reset-strobe-smoothing" title="Défaut">↺</button></div></div><div class="custom-slider-wrapper"><input type="range" id="strobe-smoothing-slider" min="0" max="99" step="1" value="80"></div></div>
        <div class="setting-item"><div class="setting-label"><span data-i18n="countdown_label">Décompte calibrage</span><div><span id="countdown-val">3s</span><button class="reset-param-btn" id="reset-countdown" title="Défaut">↺</button></div></div><div class="custom-slider-wrapper"><input type="range" id="countdown-slider" min="0" max="10" step="1" value="3"></div></div>
        <div class="setting-item"><div class="setting-label"><span data-i18n="calib_duration_label">Durée calibrage</span><div><span id="calib-duration-val">3s</span><button class="reset-param-btn" id="reset-calib-duration" title="Défaut">↺</button></div></div><div class="custom-slider-wrapper"><input type="range" id="calib-duration-slider" min="1" max="10" step="0.5" value="3"></div></div>

        <div class="app-version">Version <span id="app-version"></span></div>
    </div>

    <div id="help-panel" class="side-panel">
        <div class="panel-header"><span data-i18n="manual_title">Manuel</span><button class="close-panel-btn">×</button></div>
        <div class="help-content">
            <div class="help-section"><h4 data-i18n="help_how_title">Fonctionnement</h4><p data-i18n="help_how_text">Jouez une note. L'aiguille indique la précision en cents et le graphe montre les moyennes enregistrées. <strong>Cliquez sur le cadran</strong> pour activer le mode Stroboscope : les cercles se figent lorsque la note est parfaitement juste.</p></div>
            <div class="help-section"><h4 data-i18n="help_temp_title">Tempéraments</h4><ul><li data-i18n="help_temp_equal"><strong>TE</strong> : Tempérament Égal (Standard).</li><li data-i18n="help_temp_just"><strong>Juste</strong> : Intonation Juste (choix de la tonique).</li><li data-i18n="help_temp_bagad"><strong>Bagad</strong> : Échelle traditionnelle cornemuse/bombarde.</li><li data-i18n="help_temp_pipeband"><strong>Pipe Band</strong> : Si bémol majeur avec 7ème harmonique (7/4).</li></ul></div>
            <div class="help-section"><h4 data-i18n="help_stats_title">Moyennes & Calibrage</h4><p data-i18n="help_stats_text">Les notes tenues s'affichent sous forme de graphe dans la section "Moyennes". <strong>Cliquez sur une barre</strong> pour voir le détail et recalibrer le La4 (A4) sur cette valeur.<br><br>Utilisez le bouton <strong>Calibrer</strong> pour définir le diapason via une note de référence.</p></div>
        </div>
    </div>
    <div id="confirm-modal" class="modal-overlay">
        <div class="modal-content"><h3 style="margin-top: 0; font-weight:800; font-size:1.4rem;" data-i18n="confirm_title">Changer la tonalité ?</h3><p style="color:var(--text-muted); margin-bottom:20px;" data-i18n="confirm_text">Vous êtes déjà en mode tempérament spécifique. Voulez-vous vraiment modifier la tonalité ?</p><div style="display:flex; gap:10px;"><button id="confirm-no" style="flex:1; background:var(--input-bg); color:var(--text-main);" data-i18n="btn_cancel">Annuler</button><button id="confirm-yes" class="primary" style="flex:1;" data-i18n="btn_change">Changer</button></div></div>
    </div>
    <div id="temperament-modal" class="modal-overlay">
        <div class="modal-content"><h3 style="margin-top: 0; color: var(--text-main); font-weight:800; font-size:1.5rem;" data-i18n="temp_modal_title">Choisir le Tempérament</h3><div class="temp-grid"><button id="btn-select-equal" data-i18n="btn_temp_equal">Tempérament Égal (TE)</button><button id="btn-select-just" data-i18n="btn_temp_just">Tempérament Juste</button><button id="btn-select-bagad" data-i18n="btn_temp_bagad">Bagad</button><button id="btn-select-pipeband" data-i18n="btn_temp_pipeband">Pipe Band</button></div><button id="temp-close-btn" style="width: 100%; margin-top: 20px; background:var(--input-bg); color: var(--text-muted);" data-i18n="btn_cancel">Annuler</button></div>
    </div>
    <div id="metronome-modal" class="modal-overlay">
        <div class="modal-content">
            <h3 style="margin-top: 0; font-weight:800; font-size:1.5rem;">Métronome</h3>
            <div class="metro-controls">
                <div class="metro-bpm-row">
                    <button class="icon-btn" id="btn-metro-minus"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><rect x="5" y="11" width="14" height="2" rx="1"/></svg></button>
                    <div class="bpm-display" id="metro-bpm-container"><span id="metro-bpm-display">90</span></div>
                    <button class="icon-btn" id="btn-metro-plus"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M11 5v14h2V5h-2zm-7 7h14v-2H4v2z" transform="rotate(90 12 12)" /><path d="M11 5v14h2V5h-2zm-7 7h14v-2H4v2z"/></svg></button>
                </div>
                <div style="display:flex; justify-content:center; margin: 10px 0;">
                    <button class="metro-btn-circle" id="btn-metro-play"><svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></button>
                </div>
                <div style="margin-bottom:10px;">
                    <button id="btn-metro-tap" class="metro-tap-btn">TAP TEMPO</button>
                </div>
                <div class="metro-options">
                    <button class="metro-opt-btn active" id="btn-metro-audio" data-mode="audio">Son</button>
                    <button class="metro-opt-btn active" id="btn-metro-visual" data-mode="visual">Visuel</button>
                    <button class="metro-opt-btn" id="btn-metro-flash" data-mode="flash">Flash</button>
                </div>
            </div>
            <button id="metro-close-btn" style="width: 100%; margin-top: 20px; background:var(--input-bg); color: var(--text-muted);">Fermer</button>
        </div>
    </div>
    <div id="rename-modal" class="modal-overlay">
        <div class="modal-content">
            <h3 style="margin-top: 0; font-weight:800;" data-i18n="rename_title">Partager l'enregistrement</h3>
            <div style="margin: 20px 0;">
                <label style="display:block; text-align:left; font-size:0.9rem; color:var(--text-muted); margin-bottom:5px;" data-i18n="filename_label">Nom du fichier</label>
                <input type="text" id="filename-input" style="width: 100%; background: var(--input-bg); color: var(--text-main); border: 1px solid var(--panel-border); border-radius: 12px; padding: 12px; font-size: 1.1rem; font-family: 'Outfit', sans-serif;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button id="rename-cancel-btn" style="flex: 1; background: var(--input-bg); color:var(--text-main);" data-i18n="btn_cancel">Annuler</button>
                <button id="rename-share-btn" class="primary" style="flex: 1;" data-i18n="btn_share">Partager</button>
            </div>
        </div>
    </div>
    <div id="key-selection-modal" class="modal-overlay">
        <div class="modal-content"><h3 style="margin-top: 0; font-weight:800;" id="key-modal-title" data-i18n="key_modal_title">Tonalité de Base</h3><p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom:16px;" data-i18n="key_modal_text">Veuillez choisir la note tonique pour ce tempérament.</p><div class="key-grid" id="key-grid-buttons"></div><button class="primary" id="key-cancel-btn" style="width: 100%; margin-top: 20px;" data-i18n="btn_back">Retour</button></div>
    </div>
    <div id="pitch-input-modal" class="modal-overlay">
        <div class="modal-content"><h3 style="margin-top: 0; font-weight:800;" data-i18n="pitch_modal_title">Réglage du Diapason</h3><p style="font-size: 0.9rem; color: var(--text-muted);" data-i18n="pitch_modal_text">Entrez la fréquence du La4 (A4)</p><div class="pitch-input-wrapper"><input type="number" id="pitch-input" step="0.01" min="400" max="480" placeholder="440.0" style="background:var(--input-bg); color:var(--text-main); border-radius:12px; border:1px solid transparent;"></div><div style="display: flex; gap: 10px;"><button id="pitch-cancel-btn" style="flex: 1; background: var(--input-bg); color:var(--text-main);" data-i18n="btn_cancel">Annuler</button><button id="pitch-ok-btn" class="primary" style="flex: 1;" data-i18n="btn_validate">Valider</button></div></div>
    </div>
    <div class="tuner-container" id="tuner-container">
        <a href="https://beaumin.me/cours/" class="tuner-header-left"><img src="logo-anim.gif" class="logo-img-header" alt="BL"/></a>
        <button class="header-icon-btn metro-btn" id="open-metro" title="Métronome">
            <svg width="100%" height="100%" viewBox="0 0 1294 1294" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"> <g transform="matrix(1.263672,0,0,1.263672,-160,-160)"> <path d="M290.931,583.573C381.094,152.061 380.263,151.931 388.399,114.476C390.732,103.733 391.186,101.647 503.288,39.096C524.552,27.231 526.748,28.734 547.734,41.106C649.8,101.282 652.527,99.175 657.105,109.676C659.841,115.952 685.646,243.918 688.005,255.617C689.024,260.669 686.601,259.978 681.48,259.812C664.382,259.257 656.151,261.751 651.473,262.267C647.431,262.712 645.067,239.152 638.122,207.576C623.325,140.295 625.012,139.714 623.288,137.645C623.018,137.321 622.916,136.313 519.502,136.327C424.24,136.34 424.041,137.231 423.84,137.686C422.627,140.433 319.544,638.909 310.529,682.505C309.393,687.997 312.955,685.196 318.502,685.232C333.632,685.33 504.397,686.439 507.631,684.679C508.83,684.027 523.774,653.105 525.115,650.329C598.04,499.433 598.431,499.738 604.433,486.469C621.189,449.423 626.383,447.994 621.561,443.435C581.501,405.564 587.377,343.813 627.862,313.975C661.346,289.297 694.064,299.381 695.494,299.429C699.089,299.55 736.76,215.837 757.956,173.727C768.727,152.327 793.854,157.769 795.609,178.487C796.574,189.886 738.299,300.409 732.296,316.447C731.453,318.7 738.104,323.236 741.147,326.792C788.555,382.191 744.268,470.768 671.53,465.086C663.181,464.434 660.084,460.097 654.901,470.68C646.593,487.644 553.148,678.452 552.695,683.525C552.409,686.73 555.1,684.934 734.492,685.222C740.152,685.231 735.217,681.508 722.598,615.483C712.559,562.958 697.33,501.875 700.677,500.987C734.501,492.018 734.393,487.352 736.424,489.562C736.991,490.179 795.556,772.824 800.662,797.463C828.726,932.907 830.39,933.157 827.691,947.535C818.339,997.36 763.184,989.15 762.501,989.149C752.333,989.127 275.111,989.203 268.497,989.064C228.119,988.216 216.028,951.216 219.172,929.455C221.694,912.003 285.101,611.225 290.931,583.573Z"/> <path d="M524.504,175.067C529.347,175.078 543.239,173.75 543.287,177.51C543.315,179.652 543.668,525.821 543.073,527.384C540.861,533.196 539.948,532.699 509.296,597.404C509.016,597.994 503.742,609.127 504.036,603.643C505.955,567.84 504.328,223.552 504.171,190.501C504.105,176.571 503.623,176.495 504.536,175.538C505.608,174.416 505.769,175.034 524.504,175.067Z"/> </g> </svg>
        </button>
        <button class="header-icon-btn help-btn" id="open-help" title="Manuel d'aide"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></button>
        <button class="header-icon-btn settings-btn" id="open-settings" title="Paramètres"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg></button>
        <canvas id="strobe-canvas" width="1044" height="654" style="width: 348px; height: 218px;"></canvas>
        <div class="gauge-elements">
            <div class="gauge-arc"></div><div class="center-marker"></div><div class="guide-marker-wrapper" style="transform: rotate(-14deg); color: var(--marker-major);"><div class="guide-tick"></div></div><div class="guide-marker-wrapper" style="transform: rotate(16deg); color: var(--marker-minor);"><div class="guide-tick"></div></div>
            <div class="needle-wrapper" id="needle-wrapper"><div class="needle" id="needle"></div></div><div class="needle-pivot"></div>
        </div>
        <div class="info-display"><div class="note-name" id="note-display">&nbsp;<span class="note-octave"></span></div><div class="hz-offset-display" id="hz-offset-display"></div><div class="freq-display" id="freq-display" data-i18n="target_freq_default">0.0 Hz (cible)</div></div>
    </div>

<div class="controls-container" id="controls-container">
        <div class="control-row" style="flex-wrap: nowrap; gap: 8px;">
            
            <button class="primary" id="btn-calibrate" style="flex: 1; width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-left: 0; padding-right: 0;" data-i18n="btn_calibrate">Calibrer</button>

            <div class="hz-control">
                <button class="icon-btn" id="btn-minus"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><rect x="5" y="11" width="14" height="2" rx="1"/></svg></button>
                <div class="hz-value" id="hz-val-display" title="Cliquez pour modifier"><span id="ref-hz">446.0</span>Hz</div>
                <button class="icon-btn" id="btn-plus"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M11 5v14h2V5h-2zm-7 7h14v-2H4v2z" transform="rotate(90 12 12)" /><path d="M11 5v14h2V5h-2zm-7 7h14v-2H4v2z"/></svg></button>
            </div>

            <button class="icon-btn rec-btn" id="btn-record" title="Enregistrer" style="flex: 1; width: 0; border-radius: 14px;"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><circle cx="12" cy="12" r="8"></circle></svg></button>
            
        </div>
        <div id="temperament-info" data-i18n="temp_equal">Tempérament Égal</div>
        <div id="calibration-msg"></div>
    </div>

    <div class="player-container" id="player-container">
        <div class="player-row">
            <button class="icon-btn" id="btn-player-play"><svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></button>
            <div class="player-waveform" id="waveform-container">
                <canvas id="waveform-canvas"></canvas>
                <div id="crop-start-overlay" class="crop-overlay"></div>
                <div id="crop-end-overlay" class="crop-overlay"></div>
                <div id="crop-handle-start" class="crop-handle" style="left:0;"></div>
                <div id="crop-handle-end" class="crop-handle" style="right:0;"></div>
                <div id="playhead-marker" class="playhead-marker"></div>
            </div>
            <button class="icon-btn" id="btn-player-share" title="Partager"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>
            <button class="icon-btn" id="btn-player-close" title="Fermer"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"></path></svg></button>
        </div>
    </div>

    <div class="stats-container">
        <div class="stats-header">
            <div class="stats-title-group"><span data-i18n="stats_title">Moyennes</span><button id="btn-stats-mode" class="active-auto" data-i18n="btn_auto">Auto</button></div>
            <div class="stats-controls"><button id="btn-pause-stats" title="Pause / Reprendre"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M6 4h4v16H6zm8 0h4v16h-4z"/></svg></button><button class="danger" id="btn-reset-stats" style="display: block; padding: 6px 12px; font-size: 0.8rem; border-radius: 99px;" data-i18n="btn_reset">Réinitialiser</button></div>
        </div>
        <div id="stats-graph-container"><canvas id="stats-graph" width="930" height="540" style="width: 310px; height: 180px;"></canvas></div>
        <div id="stats-placeholder" style="text-align: center; color: var(--text-muted); padding: 10px; font-size: 0.9rem; position: absolute; top: 50%; left: 0px; width: 100%; display: block;" data-i18n="stats_placeholder">Jouez pour voir les statistiques...</div>
        <div id="stat-popup" class="stat-popup"></div>
    </div>

    <div class="footer-signature">by Éric Beaumin</div>

    <script>
        // 1. GLOBAL VARIABLES AND CONSTANTS
        const strobeCanvas = document.getElementById('strobe-canvas');
        const strobeCtx = strobeCanvas.getContext('2d');
        const statsGraph = document.getElementById('stats-graph');
        const statsCtx = statsGraph.getContext('2d');
        const gateLevelBar = document.getElementById('gate-level-bar');
        const statsPlaceholder = document.getElementById('stats-placeholder');
        const statPopup = document.getElementById('stat-popup');
        const btnRecord = document.getElementById('btn-record');
        const playerContainer = document.getElementById('player-container');
        const controlsContainer = document.getElementById('controls-container');
        const btnPlayerPlay = document.getElementById('btn-player-play');
        const btnPlayerClose = document.getElementById('btn-player-close');
        const btnPlayerShare = document.getElementById('btn-player-share');
        const renameModal = document.getElementById('rename-modal');
        const renameShareBtn = document.getElementById('rename-share-btn');
        const renameCancelBtn = document.getElementById('rename-cancel-btn');
        const filenameInput = document.getElementById('filename-input');
        const waveformCanvas = document.getElementById('waveform-canvas');
        const waveformContainer = document.getElementById('waveform-container');
        const cropStartOverlay = document.getElementById('crop-start-overlay');
        const cropEndOverlay = document.getElementById('crop-end-overlay');
        const cropHandleStart = document.getElementById('crop-handle-start');
        const cropHandleEnd = document.getElementById('crop-handle-end');
        const playheadMarker = document.getElementById('playhead-marker');
        const openMetroBtn = document.getElementById('open-metro');
        const metroModal = document.getElementById('metronome-modal');
        const btnMetroClose = document.getElementById('metro-close-btn');
        const btnMetroPlay = document.getElementById('btn-metro-play');
        const btnMetroMinus = document.getElementById('btn-metro-minus');
        const btnMetroPlus = document.getElementById('btn-metro-plus');
        const bpmDisplay = document.getElementById('metro-bpm-display');
        const btnMetroTap = document.getElementById('btn-metro-tap');
        const settingsBtn = document.getElementById('open-settings');
        const helpBtn = document.getElementById('open-help');
        const panelOverlay = document.getElementById('panel-overlay');
        const closePanelBtns = document.querySelectorAll('.close-panel-btn');
        const gateSlider = document.getElementById('gate-slider');
        const btnStatsMode = document.getElementById('btn-stats-mode');
        const btnPauseStats = document.getElementById('btn-pause-stats');
        const btnTemperamentMenu = document.getElementById('btn-temperament-menu');
        const confirmModal = document.getElementById('confirm-modal');
        const modalTempOverlay = document.getElementById('temperament-modal');
        const confirmYesBtn = document.getElementById('confirm-yes');
        const confirmNoBtn = document.getElementById('confirm-no');
        const hzValDisplay = document.getElementById('hz-val-display');
        const pitchInput = document.getElementById('pitch-input');
        const pitchOkBtn = document.getElementById('pitch-ok-btn');
        const pitchCancelBtn = document.getElementById('pitch-cancel-btn');
        const btnCalibrate = document.getElementById('btn-calibrate');
        const btnResetStats = document.getElementById('btn-reset-stats');
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        // Version de l'appli, affichée dans les paramètres.
        // À chaque mise en ligne, l'augmenter ici ET dans CACHE_VERSION de sw.js.
        const APP_VERSION = '0.1.4';
        document.getElementById('app-version').textContent = APP_VERSION;

        // Empêche le téléphone de se mettre en veille tant que l'accordeur tourne.
        // Le navigateur libère le verrou quand l'appli passe en arrière-plan :
        // il est redemandé au retour (voir visibilitychange).
        let wakeLock = null;
        async function requestWakeLock() {
            if (!('wakeLock' in navigator) || document.hidden) return;
            if (wakeLock && !wakeLock.released) return;
            try {
                wakeLock = await navigator.wakeLock.request('screen');
            } catch (e) {
                console.warn('Maintien de l\'écran allumé impossible :', e);
            }
        }

        const startBtn = document.getElementById('start-btn');
        const countdownSlider = document.getElementById('countdown-slider');
        const countdownVal = document.getElementById('countdown-val');
        const calibSlider = document.getElementById('calib-duration-slider');
        const calibVal = document.getElementById('calib-duration-val');
        const resetDelaySlider = document.getElementById('reset-delay-slider');
        const resetDelayVal = document.getElementById('reset-delay-val');
        const tunerContainerDiv = document.getElementById('tuner-container');
        const strobeSmoothSlider = document.getElementById('strobe-smoothing-slider');
        const strobeToggle = document.getElementById('toggle-strobe');
        const themeOpts = document.querySelectorAll('#theme-switcher-main .theme-opt');

        let audioContext = null;
        let analyser = null;
        let mediaStreamSource = null;
        let filter = null; 
        let dataArray = null;
        let rafID = null; 
        let audioAnalysisIntervalId = null; 
        let micStream = null; 
        let isRunning = false; 
        let autoPaused = false; 
        
        // MODIFICATION ICI : VALEURS PAR DÉFAUT (446Hz, Bagad, Si bémol)
        let referencePitch = 446.0; 
        let noiseGateThreshold = 0.01;
        let noteStats = {};
        let currentLang = 'fr';
        let temperamentMode = 'bagad'; 
        let lastCalibrationNoteNum = null;
        let justTemperamentKeyNoteNum = 10; // 10 = Si bémol (Bb)
        let settingsDefaultKey = 10;
        
        let pendingTemperamentMode = null;
        let isCalibratingManually = false;
        let calibrationState = 'idle';
        let calibrationBuffer = [];
        let isStatsAutoMode = true;
        let isStatsPaused = false;
        let isStrobeMode = false;
        let strobeEnabled = true;
        let countdownTime = 3;
        let calibrationDuration = 3;
        let strobeSmoothingFactor = 0.2;
        let smoothedStrobeOffset = 0;
        let resetButtonDelay = 0;
        const resetDelayMap = [0, 2, 5, 10];
        let isEditingDefaultSettings = false;
        let currentStableNote = null;
        let noteStabilityStart = 0;
        let lastNoteTimestamp = Date.now();
        let currentNeedleAngle = 0;
        let targetNeedleAngle = 0;
        const smoothingFactor = 0.15;
        let currentOffsetHz = 0;
        let strobePhases = [0, 0, 0, 0];
        let graphClickZones = [];
        let resetTimeoutId = null;
        let onConfirmAction = null;
        
        let mediaRecorder = null;
        let audioChunks = [];
        let recordedBlob = null;
        let audioPlayerSource = null;
        let isRecording = false;
        let isPlayingRecorded = false;
        let playerAudioBuffer = null;
        let cropStartTime = 0;
        let cropEndTime = 0;
        let playStartOffset = 0; 
        let playbackStartTime = 0;
        let waveformData = null;
        let currentRecordingPitchData = null;

        let metroBpm = 90;
        let metroIsPlaying = false;
        let metroModes = { audio: true, visual: true, flash: false };
        let metroNextNoteTime = 0.0;
        let metroTimerID = null;
        let metroLookahead = 25.0; 
        let metroScheduleAheadTime = 0.1; 
        let tapTimes = []; 
        let cameraTrack = null;

        const notesEn = ["C", "Db", "D", "Eb", "E", "F", "Gb", "G", "Ab", "A", "Bb", "B"];
        const notesEnSharps = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"];
        const notesFr = ["Do", "Réb", "Ré", "Mib", "Mi", "Fa", "Solb", "Sol", "Lab", "La", "Sib", "Si"];
        const notesFrHtml = ["Do", "Ré<i>b</i>", "Ré", "Mi<i>b</i>", "Mi", "Fa", "Sol<i>b</i>", "Sol", "La<i>b</i>", "La", "Si<i>b</i>", "Si"];
        const TJ_DEVIATIONS_C_TONIC = [0.0, +11.7, +3.9, +15.6, -13.7, -2.0, 0.0, +2.0, +13.7, -15.6, +17.6, -11.7];
        const BAGAD_DEVIATIONS = [0.0, 0.0, 0.0, 0.0, -13.7, -2.0, 0.0, +2.0, 0.0, 0.0, 0.0, 0.0];
        const PIPEBAND_DEVIATIONS = [0.0, +11.7, +3.9, +15.6, -13.7, -2.0, 0.0, +2.0, +13.7, -15.6, -31.2, -11.7];
        const GATE_MAX = 0.03;

        const translations = {
            fr: {
                "tuner_subtitle": "Accordeur", "btn_ready": "Ready ? Accordez !", "mic_required": "Accès micro requis", "settings_title": "Paramètres", "theme_label": "Thème", "theme_dark": "Nuit", "theme_light": "Jour", "theme_auto": "Auto", "default_pitch_label": "Diapason par défaut (Hz)", "default_temp_label": "Tempérament par défaut", "default_notation_label": "Notation par défaut", "gate_label": "Seuil de détection (Gate)", "reset_delay_label": "Délai bouton Réinitialiser", "strobe_label": "Afficher Stroboscope", "strobe_smooth_label": "Lissage Stroboscope", "countdown_label": "Décompte calibrage", "calib_duration_label": "Durée calibrage", "manual_title": "Manuel", "help_how_title": "Fonctionnement", "help_how_text": "Jouez une note. L'aiguille indique la précision en cents et le graphe montre les moyennes enregistrées. <strong>Cliquez sur le cadran</strong> pour activer le mode Stroboscope : les cercles se figent lorsque la note est parfaitement juste.", "help_temp_title": "Tempéraments", "help_temp_equal": "<strong>TE</strong> : Tempérament Égal (Standard).", "help_temp_just": "<strong>Juste</strong> : Intonation Juste (choix de la tonique).", "help_temp_bagad": "<strong>Bagad</strong> : Échelle traditionnelle cornemuse/bombarde.", "help_temp_pipeband": "<strong>Pipe Band</strong> : Si bémol majeur avec 7ème harmonique (7/4).", "help_stats_title": "Moyennes & Calibrage", "help_stats_text": "Les notes tenues s'affichent sous forme de graphe dans la section 'Moyennes'. <strong>Cliquez sur une barre</strong> pour voir le détail et recalibrer le La4 (A4) sur cette valeur.<br><br>Utilisez le bouton <strong>Calibrer</strong> pour définir le diapason via une note de référence.", "confirm_title": "Changer la tonalité ?", "confirm_text": "Vous êtes déjà en mode tempérament spécifique. Voulez-vous vraiment modifier la tonalité ?", "btn_cancel": "Annuler", "btn_change": "Changer", "temp_modal_title": "Choisir le Tempérament", "btn_temp_equal": "Tempérament Égal (TE)", "btn_temp_just": "Tempérament Juste", "btn_temp_bagad": "Bagad", "btn_temp_pipeband": "Pipe Band", "key_modal_title": "Tonalité de Base", "key_modal_text": "Veuillez choisir la note tonique pour ce tempérament.", "btn_back": "Retour", "pitch_modal_title": "Réglage du Diapason", "pitch_modal_text": "Entrez la fréquence du La4 (A4)", "btn_validate": "Valider", "btn_calibrate": "Calibrer", "stats_title": "Moyennes", "btn_auto": "Auto", "btn_manual": "Manuel", "btn_reset": "Réinitialiser", "stats_placeholder": "Jouez pour voir les statistiques...", "target_freq_default": "0.0 Hz (cible)", "msg_calibrating": "Calibrage…", "msg_no_sound": "Aucun son stable détecté.", "msg_tuned_to": "Calé sur", "msg_start_tuner": "Lancez d'abord l'accordeur.", "msg_calib_in": "Calibrage dans", "msg_pitch_set": "A4 réglé à", "msg_adjusted_on": "Ajusté sur", "access_denied": "Accès micro refusé. Vérifiez les permissions/HTTPS.", "temp_equal": "Tempérament Égal", "temp_just": "Juste – tonique", "temp_bagad": "Bagad – tonique", "temp_pipeband": "Pipe Band (Sib)", "bagad_pib": "La b pib", "btn_reset_countdown": "RàZ", "temp_short_just": "Juste", "target_suffix": "Hz (cible)", "btn_share": "Partager", "rename_title": "Partager l'enregistrement", "filename_label": "Nom du fichier", "share_error": "Erreur lors du partage", "share_unsupported": "Partage non supporté sur ce navigateur"
            },
            en: {
                "tuner_subtitle": "Tuner", "btn_ready": "Ready? Tune!", "mic_required": "Microphone access required", "settings_title": "Settings", "theme_label": "Theme", "theme_dark": "Dark", "theme_light": "Light", "theme_auto": "Auto", "default_pitch_label": "Default Pitch (Hz)", "default_temp_label": "Default Temperament", "default_notation_label": "Default Notation", "gate_label": "Detection Threshold (Gate)", "reset_delay_label": "Reset Button Delay", "strobe_label": "Show Strobe", "strobe_smooth_label": "Strobe Smoothing", "countdown_label": "Calibration Countdown", "calib_duration_label": "Calibration Duration", "manual_title": "Manual", "help_how_title": "How it works", "help_how_text": "Play a note. The needle shows precision in cents and the graph displays recorded averages. <strong>Click the dial</strong> to toggle Strobe mode: circles freeze when the note is perfectly in tune.", "help_temp_title": "Temperaments", "help_temp_equal": "<strong>ET</strong>: Equal Temperament (Standard).", "help_temp_just": "<strong>Just</strong>: Just Intonation (root note selection).", "help_temp_bagad": "<strong>Bagad</strong>: Traditional Breton pipe scale.", "help_temp_pipeband": "<strong>Pipe Band</strong>: Bb Major with harmonic 7th (7/4).", "help_stats_title": "Averages & Calibration", "help_stats_text": "Sustained notes appear as a graph in the \'Averages\' section. <strong>Click a bar</strong> to see details and recalibrate A4 based on that value.<br><br>Use the <strong>Calibrate</strong> button to set the pitch using a reference note.", "confirm_title": "Change Key?", "confirm_text": "You are already in a specific temperament mode. Do you really want to change the key?", "btn_cancel": "Cancel", "btn_change": "Change", "temp_modal_title": "Select Temperament", "btn_temp_equal": "Equal Temperament (ET)", "btn_temp_just": "Just Intonation (b)", "btn_temp_bagad": "Bagad (b)", "btn_temp_pipeband": "Pipe Band", "key_modal_title": "Root Key", "key_modal_text": "Please select the tonic note for this temperament.", "btn_back": "Back", "pitch_modal_title": "Pitch Adjustment", "pitch_modal_text": "Enter frequency for A4", "btn_validate": "OK", "btn_calibrate": "Calibrate", "stats_title": "Averages", "btn_auto": "Auto", "btn_manual": "Manual", "btn_reset": "Reset", "stats_placeholder": "Play to see statistics...", "target_freq_default": "0.0 Hz (target)", "msg_calibrating": "Calibrating…", "msg_no_sound": "No stable sound detected.", "msg_tuned_to": "Tuned to", "msg_start_tuner": "Start the tuner first.", "msg_calib_in": "Calibrating in", "msg_pitch_set": "A4 set to", "msg_adjusted_on": "Adjusted on", "access_denied": "Microphone access denied. Check permissions/HTTPS.", "temp_equal": "Equal Temperament", "temp_just": "Just – tonic", "temp_bagad": "Bagad – tonic", "temp_pipeband": "Pipe Band (Bb)", "bagad_pib": "Ab (Pib)", "btn_reset_countdown": "Reset", "temp_short_just": "Just", "target_suffix": "Hz (target)", "btn_share": "Share", "rename_title": "Share Recording", "filename_label": "Filename", "share_error": "Error sharing", "share_unsupported": "Sharing not supported on this browser"
            }
        };

        function t(key) { return translations[currentLang][key] || key; }

        function initAudio() {
            if (audioContext) return;
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) throw new Error("API Audio non supportée");
            audioContext = new AudioContextClass();
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 1024;
            filter = audioContext.createBiquadFilter();
            filter.type = "lowpass"; 
            filter.frequency.value = 1000;
            filter.connect(analyser);
            dataArray = new Float32Array(analyser.fftSize);
        }

        // iOS (Safari 16.4+) : tant que le mode audio reste celui d'un appel (micro),
        // la lecture sort à faible volume. On choisit le mode selon l'usage.
        function setAudioSessionType(type) {
            try { if (navigator.audioSession) navigator.audioSession.type = type; } catch (e) {}
        }

        async function startMic() {
            setAudioSessionType('play-and-record');
            if (!audioContext) initAudio();
            if (audioContext.state === 'suspended') await audioContext.resume();
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: { echoCancellation: false, autoGainControl: false, noiseSuppression: false, latency: 0 } });
                micStream = stream;
                mediaStreamSource = audioContext.createMediaStreamSource(stream);
                mediaStreamSource.connect(filter); 
            } catch (e) {
                console.error("Erreur micro", e);
                const el = document.getElementById('start-error');
                if(el) el.innerText = t('access_denied');
                throw e; 
            }
        }

        function stopMic() {
            if (micStream) { micStream.getTracks().forEach(t => t.stop()); micStream = null; }
            if (mediaStreamSource) { try { mediaStreamSource.disconnect(); } catch(e){} mediaStreamSource = null; }
        }

        function updateInterfaceText() {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (translations[currentLang][key]) el.innerHTML = translations[currentLang][key];
            });
            updateStatsModeUI(); updateTemperamentInfo();
            if (Object.keys(noteStats).length > 0) drawStatsGraph();
        }

        function getNoteName(noteNum, useHtml = false) {
            const index = ((noteNum % 12) + 12) % 12;
            const octave = Math.floor(noteNum / 12) - 1;
            let arr;
            if (currentLang === 'fr') { arr = useHtml ? notesFrHtml : notesFr; } 
            else { if (temperamentMode === 'pipeband') { arr = notesEnSharps; } else { arr = notesEn; } }
            return { index, name: arr[index], octave, full: arr[index] + octave };
        }

        function lerp(start, end, factor) { return start + (end - start) * factor; }

        function resizeCanvas() {
            const parent = strobeCanvas.parentElement;
            if(!parent) return;
            const pixelRatio = window.devicePixelRatio || 1;
            strobeCanvas.style.width = parent.clientWidth + 'px'; strobeCanvas.style.height = parent.clientHeight + 'px';
            strobeCanvas.width = parent.clientWidth * pixelRatio; strobeCanvas.height = parent.clientHeight * pixelRatio;
            strobeCtx.setTransform(1, 0, 0, 1, 0, 0); strobeCtx.scale(pixelRatio, pixelRatio);
            const graphContainer = document.getElementById('stats-graph-container');
            if(graphContainer) {
                statsGraph.style.width = graphContainer.clientWidth + 'px'; statsGraph.style.height = graphContainer.clientHeight + 'px';
                statsGraph.width = graphContainer.clientWidth * pixelRatio; statsGraph.height = graphContainer.clientHeight * pixelRatio;
                statsCtx.setTransform(1, 0, 0, 1, 0, 0); statsCtx.scale(pixelRatio, pixelRatio);
            }
            if (Object.keys(noteStats).length > 0) drawStatsGraph();
        }

        function checkEmptyStats() {
            if (Object.keys(noteStats).length === 0) { statsPlaceholder.style.display = 'block'; statsCtx.clearRect(0,0, statsGraph.width, statsGraph.height); } 
            else { statsPlaceholder.style.display = 'none'; }
        }

        function refreshStatsUI(reset) {
            if (reset) { noteStats = {}; statPopup.classList.remove('visible'); }
            checkEmptyStats();
            if (Object.keys(noteStats).length > 0) { drawStatsGraph(); } 
            else { statsCtx.clearRect(0, 0, statsGraph.width, statsGraph.height); }
        }

        function updateRefUI() { if (document.getElementById('ref-hz')) document.getElementById('ref-hz').textContent = referencePitch.toFixed(1); }

        function updateTemperamentInfo() {
            let txt = t('temp_equal'); let btnLabel = "TE";
            if (currentLang === 'en') btnLabel = "ET";
            if (temperamentMode === 'just') { const tonic = getNoteName(justTemperamentKeyNoteNum).name; txt = `${t('temp_just')} ${tonic}`; btnLabel = t('temp_short_just'); } 
            else if (temperamentMode === 'bagad') { const tonic = getNoteName(justTemperamentKeyNoteNum).name; txt = `${t('temp_bagad')} ${tonic}`; btnLabel = "Bagad"; } 
            else if (temperamentMode === 'pipeband') { txt = t('temp_pipeband'); btnLabel = "Pipe"; }
            if (document.getElementById('temperament-info')) document.getElementById('temperament-info').textContent = txt;
            if (document.getElementById('btn-temperament-menu')) document.getElementById('btn-temperament-menu').textContent = btnLabel;
        }

        function updateGateDisplay(val) { const gateValDisplay = document.getElementById('gate-val'); const percentage = Math.min(100, Math.round((val / GATE_MAX) * 100)); gateValDisplay.innerText = percentage + "%"; }
        function updateStrobeSmoothing() { 
            const val = parseInt(strobeSmoothSlider.value, 10); strobeSmoothingFactor = 1.0 - (val / 100);
            if (strobeSmoothingFactor <= 0.01) strobeSmoothingFactor = 0.01;
            const ms = Math.round((1 / strobeSmoothingFactor) * 16.66); document.getElementById('strobe-smooth-val').innerText = ms + " ms";
        }
        function updateStatsModeUI() {
            if (isStatsAutoMode) { btnStatsMode.innerText = t('btn_auto'); btnStatsMode.classList.add("active-auto"); btnStatsMode.classList.remove("active-manual"); btnResetStats.style.display = "block"; } 
            else { btnStatsMode.innerText = t('btn_manual'); btnStatsMode.classList.remove("active-auto"); btnStatsMode.classList.add("active-manual"); btnResetStats.style.display = "block"; }
        }
        function updateOptionUI(containerSelector, val) {
            const container = document.querySelector(containerSelector); if (!container) return;
            const opts = container.querySelectorAll('.theme-opt');
            opts.forEach(opt => { if (opt.dataset.val === val) opt.classList.add('active'); else opt.classList.remove('active'); });
        }
        function openPanel(panel) { closeAllPanels(); panel.classList.add('open'); panelOverlay.classList.add('open'); }
        function closeAllPanels() {
            document.querySelectorAll('.side-panel').forEach(p => p.classList.remove('open'));
            panelOverlay.classList.remove('open');
        }
        function closePitchModal() { document.getElementById('pitch-input-modal').style.display = 'none'; }
        
        function saveSettings() {
            const activeTemp = document.querySelector('#default-temp-switcher .active');
            const activeLang = document.querySelector('#default-lang-switcher .active');
            const activeThemeEl = document.querySelector('#theme-switcher-main .theme-opt.active');
            const settings = {
                theme: activeThemeEl ? activeThemeEl.dataset.val : 'light', gate: noiseGateThreshold, strobe: strobeEnabled,
                smoothing: strobeSmoothSlider.value, countdown: countdownTime, calibDuration: calibrationDuration,
                resetDelayIndex: resetDelaySlider.value, defaultPitch: parseFloat(document.getElementById('default-pitch-input').value),
                defaultTemperament: activeTemp ? activeTemp.dataset.val : 'equal', defaultKey: settingsDefaultKey,
                defaultNotation: activeLang ? activeLang.dataset.val : 'fr',
                metroBpm: metroBpm,
                metroModes: metroModes
            };
            localStorage.setItem('avelpitch_settings', JSON.stringify(settings));
        }

        function setTheme(theme) {
            if (theme === 'system') {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) document.body.setAttribute('data-theme', 'light');
                else document.body.setAttribute('data-theme', 'dark');
            } else { document.body.setAttribute('data-theme', theme); }
            updateOptionUI('#theme-switcher-main', theme);
            if (Object.keys(noteStats).length > 0) setTimeout(drawStatsGraph, 100);
        }

        function loadSettings() {
            const saved = localStorage.getItem('avelpitch_settings');
            if (!saved) { setTheme('light'); return; }
            const s = JSON.parse(saved);
            if (s.theme) setTheme(s.theme);
            if (s.gate !== undefined) { noiseGateThreshold = s.gate; gateSlider.value = s.gate; updateGateDisplay(s.gate); }
            if (s.strobe !== undefined) { 
                strobeEnabled = s.strobe; 
                if(strobeEnabled) { strobeToggle.classList.add('on'); strobeCanvas.style.display = 'block'; }
                else { strobeToggle.classList.remove('on'); strobeCanvas.style.display = 'none'; if(isStrobeMode) { isStrobeMode=false; tunerContainerDiv.classList.remove('strobe-mode');}}
            }
            if (s.smoothing !== undefined) { strobeSmoothSlider.value = s.smoothing; updateStrobeSmoothing(); }
            if (s.countdown !== undefined) { countdownTime = s.countdown; countdownSlider.value = s.countdown; countdownVal.innerText = s.countdown + 's'; }
            if (s.calibDuration !== undefined) { calibrationDuration = s.calibDuration; calibSlider.value = s.calibDuration; calibVal.innerText = s.calibDuration + 's'; }
            if (s.resetDelayIndex !== undefined) { resetDelaySlider.value = s.resetDelayIndex; resetButtonDelay = resetDelayMap[s.resetDelayIndex]; resetDelayVal.innerText = resetButtonDelay + 's'; }
            if (s.defaultPitch !== undefined) { referencePitch = s.defaultPitch; document.getElementById('default-pitch-input').value = s.defaultPitch; }
            updateRefUI();
            if (s.defaultKey !== undefined) { settingsDefaultKey = s.defaultKey; justTemperamentKeyNoteNum = s.defaultKey; }
            if (s.defaultTemperament !== undefined) { updateOptionUI('#default-temp-switcher', s.defaultTemperament); setTemperamentMode(s.defaultTemperament, s.defaultKey); }
            if (s.defaultNotation !== undefined) { updateOptionUI('#default-lang-switcher', s.defaultNotation); currentLang = s.defaultNotation; updateInterfaceText(); refreshStatsUI(false); populateKeySelectionModal(); saveSettings(); }
            if (s.metroBpm !== undefined) { metroBpm = s.metroBpm; bpmDisplay.textContent = metroBpm; }
            if (s.metroModes !== undefined) {
                metroModes = s.metroModes;
                // Update buttons UI
                document.getElementById('btn-metro-audio').classList.toggle('active', metroModes.audio);
                document.getElementById('btn-metro-visual').classList.toggle('active', metroModes.visual);
const flashBtn = document.getElementById('btn-metro-flash');
                if(flashBtn) flashBtn.classList.toggle('active', metroModes.flash);
            }
            // AJOUT : Synchronise l'état visuel du nouveau sélecteur
            updateOptionUI('#active-temp-switcher', temperamentMode);
        }
        function noteFromPitch(frequency) { return Math.round(12 * (Math.log(frequency / referencePitch) / Math.log(2))) + 69; }
        function getTemperamentOffset(noteIndex) {
            if (temperamentMode === 'just') { return TJ_DEVIATIONS_C_TONIC[(noteIndex - justTemperamentKeyNoteNum + 12) % 12]; }
            if (temperamentMode === 'bagad') { return BAGAD_DEVIATIONS[(noteIndex - justTemperamentKeyNoteNum + 12) % 12]; }
            if (temperamentMode === 'pipeband') { return PIPEBAND_DEVIATIONS[(noteIndex - 10 + 12) % 12]; }
            return 0;
        }
        function frequencyFromNoteNumber(note, offsetCents = null) {
            let frequency = referencePitch * Math.pow(2, (note - 69) / 12);
            if (offsetCents !== null) { frequency *= Math.pow(2, offsetCents / 1200); } 
            else if (temperamentMode !== 'equal') { frequency *= Math.pow(2, getTemperamentOffset(note % 12) / 1200); }
            return frequency;
        }
        function centsOffFromFreq(frequency, targetFreq) { return Math.floor(1200 * Math.log(frequency / targetFreq) / Math.log(2)); }

        function autoCorrelate(buf, sampleRate) {
            let SIZE = buf.length; let rms = 0;
            for (let i = 0; i < SIZE; i++) rms += buf[i] * buf[i];
            rms = Math.sqrt(rms / SIZE);
            if (gateLevelBar) {
                let pct = (rms / GATE_MAX) * 100; if (pct > 100) pct = 100; gateLevelBar.style.width = pct + "%";
                gateLevelBar.style.backgroundColor = (rms < noiseGateThreshold) ? "var(--text-muted)" : "var(--success)";
                gateLevelBar.style.opacity = (rms < noiseGateThreshold) ? "0.5" : "0.8";
            }
            if (rms < noiseGateThreshold) return -1;
            let r1 = 0, r2 = SIZE - 1, thres = 0.2;
            for (let i = 0; i < SIZE / 2; i++) if (Math.abs(buf[i]) < thres) { r1 = i; break; }
            for (let i = 1; i < SIZE / 2; i++) if (Math.abs(buf[SIZE - i]) < thres) { r2 = SIZE - i; break; }
            buf = buf.slice(r1, r2); SIZE = buf.length;
            let maxLag = Math.min(SIZE - 1, Math.floor(sampleRate / 60)); if (maxLag <= 0) return -1;
            const c = new Array(maxLag).fill(0);
            for (let i = 0; i < maxLag; i++) { let sum = 0; for (let j = 0; j < SIZE - i; j++) sum += buf[j] * buf[j + i]; c[i] = sum; }
            let d = 0; while (d + 1 < maxLag && c[d] > c[d + 1]) d++;
            let maxval = -1, maxpos = -1;
            for (let i = d; i < maxLag; i++) if (c[i] > maxval) { maxval = c[i]; maxpos = i; }
            if (maxpos <= 0) return -1;
            let T0 = maxpos;
            if (T0 > 0 && T0 < maxLag - 1) {
                const x1 = c[T0 - 1], x2 = c[T0], x3 = c[T0 + 1];
                const a = (x1 + x3 - 2 * x2) / 2; const b = (x3 - x1) / 2;
                if (a) T0 = T0 - b / (2 * a);
            }
            return sampleRate / T0;
        }

        function setTemperamentMode(mode, keyNoteNum) {
            temperamentMode = mode;
            if (mode === 'pipeband') justTemperamentKeyNoteNum = 10;
            else if (typeof keyNoteNum === 'number') justTemperamentKeyNoteNum = ((keyNoteNum % 12) + 12) % 12;
            updateTemperamentInfo(); if (Object.keys(noteStats).length > 0) drawStatsGraph();
        }

        function handleKeySelection(noteIndex) {
            justTemperamentKeyNoteNum = noteIndex;
if (pendingTemperamentMode) {
                setTemperamentMode(pendingTemperamentMode, noteIndex);
                if (isEditingDefaultSettings) { 
                    updateOptionUI('#default-temp-switcher', pendingTemperamentMode); 
                    settingsDefaultKey = noteIndex; 
                } else {
                    // AJOUT : Mise à jour du sélecteur "actuel" si on ne modifie pas les défauts
                    updateOptionUI('#active-temp-switcher', pendingTemperamentMode);
                }
                pendingTemperamentMode = null;
            }
            isEditingDefaultSettings = false; document.getElementById('key-selection-modal').style.display = 'none';
            updateTemperamentInfo(); refreshStatsUI(false); saveSettings();
        }

        function populateKeySelectionModal() {
            const keyGridButtons = document.getElementById('key-grid-buttons'); if (!keyGridButtons) return;
            keyGridButtons.innerHTML = '';
            const names = currentLang === 'fr' ? notesFr : notesEn;
            for (let i = 0; i < 12; i++) {
                const btn = document.createElement('button'); btn.textContent = names[i];
                btn.style.background = "var(--input-bg)"; btn.style.padding = "10px";
                btn.addEventListener('click', () => handleKeySelection(i)); keyGridButtons.appendChild(btn);
            }
        }

        function updateStats(statId, frequency) {
            if (isStatsPaused) return;
            if (!noteStats[statId]) noteStats[statId] = { totalFreq: 0, count: 0 };
            noteStats[statId].totalFreq += frequency; noteStats[statId].count++;
            checkEmptyStats();
            if (noteStats[statId].count % 3 === 0) drawStatsGraph();
        }

        function drawStrobe(isAnimated) {
            const w = strobeCanvas.width / (window.devicePixelRatio || 1); strobeCtx.clearRect(0, 0, w, 220);
            const centerX = w / 2, centerY = 190, baseRadius = 140, bandWidth = 24;
            const segmentsMap = [32, 16, 8, 4], speedMap = [4, 3, 2, 1];
            smoothedStrobeOffset = lerp(smoothedStrobeOffset, currentOffsetHz, strobeSmoothingFactor);
            const isLight = document.body.getAttribute('data-theme') === 'light';
            const baseColor = isLight ? "51, 65, 85" : "255, 255, 255";
            for (let b = 0; b < 4; b++) {
                const radius = baseRadius - (b * bandWidth);
                if (isAnimated) strobePhases[b] += (smoothedStrobeOffset * speedMap[b] * (Math.PI / 8)) / 60;
                const segments = segmentsMap[b], segmentAngle = (Math.PI * 2) / segments;
                let alpha = isStrobeMode ? 0.9 - (b * 0.2) : 0.1;
                strobeCtx.save(); strobeCtx.translate(centerX, centerY); strobeCtx.rotate(strobePhases[b]);
                for (let i = 0; i < segments; i++) {
                    const color = (i % 2 === 0) ? `rgba(${baseColor}, ${alpha})` : "transparent";
                    strobeCtx.fillStyle = color; strobeCtx.beginPath();
                    strobeCtx.arc(0, 0, radius, i * segmentAngle, (i + 1) * segmentAngle);
                    strobeCtx.arc(0, 0, radius - bandWidth, (i + 1) * segmentAngle, i * segmentAngle, true);
                    strobeCtx.closePath(); strobeCtx.fill();
                }
                strobeCtx.restore();
            }
        }

        function drawStatsGraph() {
            if (Object.keys(noteStats).length === 0) { statsCtx.clearRect(0, 0, statsGraph.width, statsGraph.height); return; }
            const w = statsGraph.width / (window.devicePixelRatio || 1);
            const h = statsGraph.height / (window.devicePixelRatio || 1);
            const computedStyle = getComputedStyle(document.documentElement);
            const colorStatText = computedStyle.getPropertyValue('--stat-text').trim();
            const colorSharp = computedStyle.getPropertyValue('--stat-line-sharp').trim();
            const colorFlat = computedStyle.getPropertyValue('--color-flat-blue').trim(); 
            const colorPerfect = computedStyle.getPropertyValue('--stat-line-perfect').trim();
            const colorTonicText = computedStyle.getPropertyValue('--stat-tonic').trim();
            const gridLineColor = computedStyle.getPropertyValue('--graph-grid').trim() || 'rgba(255,255,255,0.1)';
            const colorCenter = computedStyle.getPropertyValue('--text-muted').trim();
            statsCtx.clearRect(0, 0, w, h);
            const centerY = (h - 30) / 2;
            const drawGridLine = (cents, style, width) => {
                const y = centerY - (cents * (centerY / 50));
                statsCtx.beginPath(); statsCtx.strokeStyle = style; statsCtx.lineWidth = width;
                statsCtx.moveTo(0, y); statsCtx.lineTo(w, y); statsCtx.stroke();
            };
            drawGridLine(0, colorCenter, 2);
            for (let c = 10; c <= 50; c += 10) { drawGridLine(c, gridLineColor, 1); drawGridLine(-c, gridLineColor, 1); }
            const entries = Object.entries(noteStats).map(([key, data]) => {
                const noteNum = parseInt(key.split('_')[0], 10);
                const isPib = key.includes('_pib');
                return { key, data, noteNum, isPib };
            }).sort((a, b) => { if (a.noteNum !== b.noteNum) return a.noteNum - b.noteNum; return a.isPib ? -1 : 1; });
            const stepX = w / (entries.length + 1); graphClickZones = [];
            entries.forEach((item, index) => {
                const x = stepX * (index + 1);
                const avgFreq = item.data.totalFreq / item.data.count;
                let targetFreq = item.isPib ? frequencyFromNoteNumber(item.noteNum, 0) * Math.pow(2, -31 / 1200) : frequencyFromNoteNumber(item.noteNum);
                let cents = 1200 * (Math.log(avgFreq / targetFreq) / Math.log(2));
                const y = centerY - (Math.max(-50, Math.min(50, cents)) * (centerY / 50));
                let barColor = (cents > 5) ? colorSharp : ((cents < -5) ? colorFlat : colorPerfect);
                statsCtx.fillStyle = barColor; statsCtx.strokeStyle = barColor; statsCtx.lineWidth = 2;
                statsCtx.beginPath(); statsCtx.moveTo(x, centerY); statsCtx.lineTo(x, y); statsCtx.stroke();
                statsCtx.beginPath(); statsCtx.arc(x, y, 9, 0, Math.PI * 2); statsCtx.fill();
                let centsText = (cents > 0 ? "+" : "") + cents.toFixed(0);
                statsCtx.font = "bold 13px 'Outfit', sans-serif"; statsCtx.textAlign = "center";
                statsCtx.fillText(centsText, x, y + (cents > 0 ? -16 : 24));
                let displayNoteNum = item.noteNum;
                if (temperamentMode === 'pipeband' && currentLang === 'en') displayNoteNum = item.noteNum - 1;
                let label = item.isPib ? t('bagad_pib') : getNoteName(displayNoteNum, false).name;
                let isTonic = (temperamentMode !== 'equal' && (item.noteNum % 12) === justTemperamentKeyNoteNum);
                statsCtx.fillStyle = isTonic ? colorTonicText : colorStatText;
                statsCtx.font = isTonic ? "800 18px 'Outfit', sans-serif" : "600 15px 'Outfit', sans-serif";
                statsCtx.fillText(label, x, h - 5);
                graphClickZones.push({ id: item.key, x: x - 20, w: 40 });
            });
        }

        window.calibrateFromStat = function(statId) {
            const noteNum = parseInt(statId.toString().split('_')[0], 10);
            const targetKeyIndex = noteNum % 12;
            const doCalibration = () => {
                const data = noteStats[statId]; if (!data || data.count === 0) return;
                const avgFreq = data.totalFreq / data.count;
                let deviationFactor = 1.0;
                if (statId.toString().includes('_pib')) deviationFactor = Math.pow(2, -31 / 1200);
                else if (temperamentMode !== 'equal') deviationFactor = Math.pow(2, getTemperamentOffset(noteNum % 12) / 1200);
                const standardFactor = Math.pow(2, (noteNum - 69) / 12);
                referencePitch = Math.round((avgFreq / (standardFactor * deviationFactor)) * 100) / 100;
                lastCalibrationNoteNum = noteNum % 12;
                if (temperamentMode !== 'pipeband') justTemperamentKeyNoteNum = noteNum % 12;
                else justTemperamentKeyNoteNum = 10;
                updateRefUI(); saveSettings(); if (temperamentMode !== 'equal') updateTemperamentInfo();
                let displayNoteNum = noteNum; if (temperamentMode === 'pipeband' && currentLang === 'en') displayNoteNum = noteNum - 1;
                let noteName = statId.toString().includes('_pib') ? t('bagad_pib') : getNoteName(displayNoteNum).name;
                const msg = document.getElementById('calibration-msg');
                msg.innerText = `${t('msg_adjusted_on')} ${noteName} -> A4=${referencePitch.toFixed(1)} Hz`;
                msg.style.color = "var(--accent)"; setTimeout(() => { msg.innerText = ""; }, 3000);
                refreshStatsUI(false);
            };
            if (temperamentMode === 'just' && targetKeyIndex !== justTemperamentKeyNoteNum) { onConfirmAction = doCalibration; confirmModal.style.display = 'flex'; }
            else { doCalibration(); }
        };

        function openStatPopup(statId) {
            const data = noteStats[statId]; if (!data) return;
            const noteNum = parseInt(statId.toString().split('_')[0], 10);
            const isPib = statId.toString().includes('_pib');
            let avgFreq = data.totalFreq / data.count;
            let targetFreq = isPib ? frequencyFromNoteNumber(noteNum, 0) * Math.pow(2, -31 / 1200) : frequencyFromNoteNumber(noteNum);
            let avg = 1200 * (Math.log(avgFreq / targetFreq) / Math.log(2));
            let displayNoteNum = noteNum; if (temperamentMode === 'pipeband' && currentLang === 'en') displayNoteNum = noteNum - 1;
            let noteInfo = getNoteName(displayNoteNum, true); if (isPib) noteInfo.name = t('bagad_pib');
            let textColor = (avg > 5) ? 'var(--danger)' : ((avg < -5) ? 'var(--color-flat-blue)' : 'var(--success)');
            if (isPib) textColor = 'var(--gauge-color)';
            statPopup.innerHTML = `<div class="stat-card"><div class="stat-content"><span class="stat-note">${noteInfo.name}</span><span class="stat-val" style="color:${textColor}">${(avg > 0 ? '+' : '')}${avg.toFixed(1)} ct</span><div class="popup-actions" style="margin-top:8px;"><button class="popup-btn-calib" onclick="calibrateFromStat('${statId}'); document.getElementById('stat-popup').classList.remove('visible');" style="background:var(--accent); color:white; font-size:0.8rem; padding:4px 8px; border-radius:8px;">${t('btn_calibrate')}</button></div></div></div>`;
            statPopup.style.bottom = "50%"; statPopup.style.left = "50%"; statPopup.style.transform = "translate(-50%, 50%)"; statPopup.classList.add('visible');
        }

        function processAudioFrame() {
            if (!analyser || !audioContext || audioContext.state !== 'running') return;
            analyser.getFloatTimeDomainData(dataArray);
            const frequency = autoCorrelate(dataArray, audioContext.sampleRate);
            if (calibrationState === 'listening' && frequency !== -1) calibrationBuffer.push(frequency);
            const noteDisplay = document.getElementById('note-display');
            const hzOffsetDisplay = document.getElementById('hz-offset-display');
            const needle = document.getElementById('needle');
            const gaugeArc = document.querySelector('.gauge-arc');
            if (frequency !== -1) {
                if (isStatsAutoMode && !isStatsPaused && (Date.now() - lastNoteTimestamp > 3000)) { if (Object.keys(noteStats).length > 0) { noteStats = {}; refreshStatsUI(true); } }
                lastNoteTimestamp = Date.now();
                const noteNum = noteFromPitch(frequency);
                let displayNoteNum = noteNum; if (temperamentMode === 'pipeband' && currentLang === 'en') displayNoteNum = noteNum - 1;
                let noteInfo = getNoteName(displayNoteNum, true);
                let targetFreq = frequencyFromNoteNumber(noteNum); let isPib = false;
                if (temperamentMode === 'bagad' && justTemperamentKeyNoteNum === 10 && (noteNum % 12) === 8) {
                    const stdFreq = frequencyFromNoteNumber(noteNum, 0); const pibFreq = stdFreq * Math.pow(2, -31 / 1200);
                    if (Math.abs(frequency - pibFreq) < Math.abs(frequency - stdFreq)) { isPib = true; targetFreq = pibFreq; noteInfo.name = t('bagad_pib'); } else { targetFreq = stdFreq; }
                }
                const cents = centsOffFromFreq(frequency, targetFreq);
                currentOffsetHz = frequency - targetFreq;
                noteDisplay.innerHTML = `${noteInfo.name}<span class="note-octave">${noteInfo.octave}</span>`;
                if (isPib) noteDisplay.style.color = "var(--gauge-color)";
                document.getElementById('freq-display').innerText = targetFreq.toFixed(1) + " " + t('target_suffix');
                hzOffsetDisplay.innerText = `${(cents >= 0 ? '+' : '')}${cents} ct`;
                targetNeedleAngle = Math.max(-50, Math.min(50, cents));
                if (Math.abs(cents) < 4) {
                    needle.style.background = "var(--success)"; gaugeArc.style.borderColor = "var(--success)";
                    if (!isPib) noteDisplay.style.color = "var(--success)"; hzOffsetDisplay.style.color = "var(--success)";
                } else {
                    needle.style.background = "var(--needle-color)"; gaugeArc.style.borderColor = "var(--gauge-color)";
                    if (!isPib) noteDisplay.style.color = "var(--text-main)";
                    hzOffsetDisplay.style.color = (cents > 0) ? "var(--danger)" : "var(--warning)";
                }
                let statId = noteNum + (isPib ? "_pib" : "");
                if (statId !== currentStableNote) { currentStableNote = statId; noteStabilityStart = performance.now(); }
                if (calibrationState === 'idle' && !isCalibratingManually && (performance.now() - noteStabilityStart > 200)) { updateStats(statId, frequency); }
            } else {
                currentOffsetHz = 0; noteDisplay.innerHTML = `&nbsp;<span class="note-octave"></span>`;
                document.getElementById('freq-display').innerText = "0.0 " + t('target_suffix');
                hzOffsetDisplay.innerText = "";
                needle.style.background = "var(--needle-color)"; gaugeArc.style.borderColor = "var(--gauge-color)";
                noteDisplay.style.color = "var(--text-main)"; targetNeedleAngle = 0; currentStableNote = null;
            }
        }

        // --- NOUVELLES VARIABLES POUR L'ÉCONOMIE D'ÉNERGIE (WEBAPP & MOBILE) ---
        let lastFrameTime = 0;
        const targetFPS = 30;             // Cible 30 FPS pour réduire la charge GPU
        const frameInterval = 1000 / targetFPS; // ~33ms

        function animationLoop(currentTime) {
            // Si l'onglet/app est caché, on arrête tout (géré par le visibilityChange)
            if (document.hidden) { rafID = null; return; }

            // Demande la prochaine frame tout de suite (pour garder la boucle active)
            rafID = requestAnimationFrame(animationLoop);

            // Si currentTime n'est pas fourni (premier appel), on l'initialise
            if (!currentTime) currentTime = performance.now();

            // Calcul du temps écoulé depuis le dernier dessin
            const elapsed = currentTime - lastFrameTime;

            // --- LE FILTRE D'ÉCONOMIE D'ÉNERGIE ---
            // On ne dessine que si 33ms se sont écoulées
            if (elapsed > frameInterval) {
                
                // On cale le temps pour éviter la dérive
                lastFrameTime = currentTime - (elapsed % frameInterval);

                // --- CODE DE DESSIN ORIGINAL (Exécuté seulement 30 fois/sec) ---
                const needleWrapper = document.getElementById('needle-wrapper');
                
                // Lissage de l'aiguille
                currentNeedleAngle = lerp(currentNeedleAngle, targetNeedleAngle, smoothingFactor);
                if (Math.abs(targetNeedleAngle - currentNeedleAngle) < 0.1) currentNeedleAngle = targetNeedleAngle;
                
                // Mise à jour visuelle (DOM)
                if (needleWrapper) needleWrapper.style.transform = `rotate(${currentNeedleAngle}deg)`;
                
                // Mise à jour Stroboscope (Canvas)
                if (strobeEnabled) drawStrobe(isStrobeMode);
                
                // Mise à jour Waveform (Canvas)
                if (isPlayingRecorded && playerAudioBuffer) {
                    const playTime = audioContext.currentTime - playbackStartTime + playStartOffset;
                    if (playTime >= cropEndTime) stopPlayback();
                    else drawWaveform(playerAudioBuffer.getChannelData(0));
                }
            }
        }

        function startCalibrationCapture() {
            calibrationState = 'listening'; calibrationBuffer = [];
            const msg = document.getElementById('calibration-msg'); msg.style.color = "var(--accent)"; msg.innerText = t('msg_calibrating');
            setTimeout(() => {
                calibrationState = 'idle'; isCalibratingManually = false;
                if (calibrationBuffer.length === 0) { msg.style.color = "var(--danger)"; msg.innerText = t('msg_no_sound'); setTimeout(() => { msg.innerText = ""; }, 2000); return; }
                const avgFreq = calibrationBuffer.reduce((a, b) => a + b, 0) / calibrationBuffer.length;
                const approxNote = noteFromPitch(avgFreq);
                let deviationFactor = 1.0;
                if (temperamentMode !== 'equal') deviationFactor = Math.pow(2, getTemperamentOffset(approxNote % 12) / 1200);
                referencePitch = Math.round((avgFreq / (Math.pow(2, (approxNote - 69) / 12) * deviationFactor)) * 100) / 100;
                lastCalibrationNoteNum = approxNote % 12;
                if (temperamentMode === 'pipeband') justTemperamentKeyNoteNum = 10; else justTemperamentKeyNoteNum = approxNote % 12;
                updateRefUI(); saveSettings();
                let displayNoteNum = approxNote; if (temperamentMode === 'pipeband' && currentLang === 'en') displayNoteNum = approxNote - 1;
                msg.style.color = "var(--accent)"; msg.innerText = `${t('msg_tuned_to')} ${getNoteName(displayNoteNum).name} – A4=${referencePitch.toFixed(1)} Hz`;
                setTimeout(() => { msg.innerText = ""; }, 3000);
            }, Math.max(100, calibrationDuration * 1000));
        }

        function handleCalibrationClick() {
            const msg = document.getElementById('calibration-msg');
            if (!audioContext || !analyser) { msg.innerText = t('msg_start_tuner'); msg.style.color = "var(--danger)"; setTimeout(() => { msg.innerText = ""; }, 2000); return; }
            if (calibrationState !== 'idle') return;
            isCalibratingManually = true; calibrationState = 'countdown'; calibrationBuffer = [];
            let remaining = countdownTime;
            if (countdownTime > 0) {
                msg.style.color = "var(--text-main)"; msg.innerText = `${t('msg_calib_in')} ${remaining}s…`;
                const intv = setInterval(() => {
                    remaining--;
                    if (remaining <= 0) { clearInterval(intv); startCalibrationCapture(); }
                    else { msg.innerText = `${t('msg_calib_in')} ${remaining}s…`; }
                }, 1000);
            } else { startCalibrationCapture(); }
        }

        function startAudioProcessing() {
            if (!analyser || !audioContext) return;
            if (!audioAnalysisIntervalId) {
                audioAnalysisIntervalId = setInterval(() => {
                    if (document.hidden || !audioContext || audioContext.state !== 'running') return;
                    processAudioFrame();
                }, 50);
            }
            if (!rafID) rafID = requestAnimationFrame(animationLoop);
        }

        function stopAudioProcessing() { if (audioAnalysisIntervalId) { clearInterval(audioAnalysisIntervalId); audioAnalysisIntervalId = null; } if (rafID) { cancelAnimationFrame(rafID); rafID = null; } }
        function stopTuner() { isRunning = false; stopAudioProcessing(); stopMic(); if (audioContext) { audioContext.close(); audioContext = null; } }

        function analyzeRecording(buffer) {
            const rawData = buffer.getChannelData(0);
            const sr = buffer.sampleRate;
            const chunkSize = 2048; // ~46ms at 44.1k
            const pitchData = []; 
            const tempStats = {};

            // Buffer pour accumuler les blocs consécutifs d'une même note
            let sequenceBuffer = [];

            // Fonction pour traiter une séquence terminée
            const processSequence = (seq) => {
                // Filtre de stabilité : on ignore les notes détectées sur moins de 3 blocs (~140ms)
                if (seq.length >= 3) {
                    seq.forEach(item => {
                        // Ajout aux stats
                        const statId = item.noteNum + (item.isPib ? "_pib" : "");
                        if (!tempStats[statId]) tempStats[statId] = { totalFreq: 0, count: 0 };
                        tempStats[statId].totalFreq += item.freq;
                        tempStats[statId].count++;

                        // Ajout aux données de visualisation (avec couleur)
                        pitchData.push({ t: item.t, cents: item.cents, noteNum: item.noteNum, isPib: item.isPib, freq: item.freq });
                    });
                } else {
                    // Séquence trop courte = bruit/transitoire -> on l'affiche en "neutre" (cents: null)
                    seq.forEach(item => {
                        pitchData.push({ t: item.t, cents: null });
                    });
                }
            };

            for (let i = 0; i < rawData.length; i += chunkSize) {
                const chunk = rawData.slice(i, i + chunkSize);
                if (chunk.length < 1024) break; 
                
                const freq = autoCorrelate(chunk, sr);
                const t = i / sr;
                
                if (freq !== -1) {
                    const noteNum = noteFromPitch(freq);
                    let isPib = false;
                    let targetFreq = frequencyFromNoteNumber(noteNum);
                    
                    if (temperamentMode === 'bagad' && justTemperamentKeyNoteNum === 10 && (noteNum % 12) === 8) {
                         const stdFreq = frequencyFromNoteNumber(noteNum, 0); 
                         const pibFreq = stdFreq * Math.pow(2, -31 / 1200);
                         if (Math.abs(freq - pibFreq) < Math.abs(freq - stdFreq)) { isPib = true; targetFreq = pibFreq; } else { targetFreq = stdFreq; }
                    }
                    
                    const cents = centsOffFromFreq(freq, targetFreq);
                    const currentItem = { t, cents, noteNum, isPib, freq };

                    // Logique de regroupement
                    if (sequenceBuffer.length > 0) {
                        const last = sequenceBuffer[sequenceBuffer.length - 1];
                        // Si c'est la même note, on continue la séquence
                        if (last.noteNum === noteNum && last.isPib === isPib) {
                            sequenceBuffer.push(currentItem);
                        } else {
                            // Note différente : on traite la séquence précédente et on en commence une nouvelle
                            processSequence(sequenceBuffer);
                            sequenceBuffer = [currentItem];
                        }
                    } else {
                        sequenceBuffer.push(currentItem);
                    }

                } else {
                    // Silence détecté
                    if (sequenceBuffer.length > 0) {
                        processSequence(sequenceBuffer);
                        sequenceBuffer = [];
                    }
                    pitchData.push({ t: t, cents: null }); 
                }
            }
            
            // Traiter la dernière séquence si elle existe
            if (sequenceBuffer.length > 0) {
                processSequence(sequenceBuffer);
            }

            return { pitchData, tempStats };
        }

// Gestion de la visibilité pour économiser l'énergie
        document.addEventListener('visibilitychange', async () => {
            if (document.hidden) {
                // CAS 1 : L'utilisateur quitte l'onglet ou réduit l'app
                
                // 1. On suspend le contexte audio (arrête le traitement DSP)
                if (audioContext && audioContext.state === 'running') {
                    try { await audioContext.suspend(); } catch(e) {}
                }
                
                // 2. On coupe totalement le micro (éteint le témoin matériel)
                stopMic(); 
                
                // 3. On arrête la boucle de calcul (requestAnimationFrame)
                stopAudioProcessing();
                
            } else {
                // CAS 2 : L'utilisateur revient sur la page
                
                // On ne relance que si l'accordeur était en train de tourner avant
                if (isRunning) {
                    requestWakeLock();
                    // 1. On réveille le contexte audio
                    if (audioContext && audioContext.state === 'suspended') {
                        try { await audioContext.resume(); } catch(e) {}
                    }
                    
                    // 2. On redemande l'accès au micro (nécessaire car on l'a coupé avec stopMic)
                    try {
                        // startMic vérifie si audioContext existe déjà
                        await startMic(); 
                        
                        // 3. On relance l'affichage et les calculs
                        resizeCanvas();
                        startAudioProcessing();
                    } catch (e) {
                        console.error("Impossible de relancer le micro automatiquement", e);
                        // Si le navigateur bloque la reprise auto, on arrête proprement
                        // L'utilisateur devra recliquer sur le bouton Start
                        isRunning = false;
                    }
                }
            }
        });

        // Le micro est capté sans gain automatique (pour la justesse de l'accordeur) :
        // l'enregistrement est souvent faible. On le remonte pour que le pic atteigne
        // environ -1 dB, sans dépasser x20 pour ne pas faire ressortir le souffle d'un silence.
        function normalizeBuffer(buffer, targetPeak = 0.89, maxGain = 20) {
            let peak = 0;
            for (let c = 0; c < buffer.numberOfChannels; c++) {
                const d = buffer.getChannelData(c);
                for (let i = 0; i < d.length; i++) { const a = Math.abs(d[i]); if (a > peak) peak = a; }
            }
            if (peak === 0) return 1;
            const gain = Math.min(targetPeak / peak, maxGain);
            if (gain <= 1) return 1;
            for (let c = 0; c < buffer.numberOfChannels; c++) {
                const d = buffer.getChannelData(c);
                for (let i = 0; i < d.length; i++) d[i] *= gain;
            }
            return gain;
        }

        // Event Listeners
        if (btnRecord) btnRecord.addEventListener('click', async () => {
            if (isPlayingRecorded) return;
            if (isRecording) {
                mediaRecorder.stop(); btnRecord.classList.remove('recording'); isRecording = false;
            } else {
                if (!micStream) await startMic();
                if (!micStream) return;
                audioChunks = []; mediaRecorder = new MediaRecorder(micStream);
                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.onstop = async () => {
                    recordedBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    const arrayBuffer = await recordedBlob.arrayBuffer();
                    playerAudioBuffer = await audioContext.decodeAudioData(arrayBuffer);
                    
                    // Perform Analysis
                    const result = analyzeRecording(playerAudioBuffer);
                    currentRecordingPitchData = result.pitchData;
                    noteStats = result.tempStats;
                    isStatsPaused = true;
                    if(btnPauseStats) {
                        btnPauseStats.classList.add('paused');
                        btnPauseStats.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
                    }
                    drawStatsGraph();

                    normalizeBuffer(playerAudioBuffer);
                    cropStartTime = 0; cropEndTime = playerAudioBuffer.duration;
                    stopMic(); 
                    controlsContainer.style.display = 'none'; playerContainer.style.display = 'flex';
                    drawWaveform(playerAudioBuffer.getChannelData(0)); updateCropUI();
                };
                mediaRecorder.start(); isRecording = true; btnRecord.classList.add('recording');
            }
        });

        function updateCropUI() {
            if (!playerAudioBuffer) return;
            const w = waveformContainer.clientWidth;
            cropStartOverlay.style.width = (cropStartTime / playerAudioBuffer.duration) * 100 + '%';
            cropEndOverlay.style.width = (100 - (cropEndTime / playerAudioBuffer.duration) * 100) + '%'; cropEndOverlay.style.right = '0'; cropEndOverlay.style.left = 'auto';
            cropHandleStart.style.left = (cropStartTime / playerAudioBuffer.duration) * 100 + '%';
            cropHandleEnd.style.left = (cropEndTime / playerAudioBuffer.duration) * 100 + '%';
        }

        let isDraggingStart = false, isDraggingEnd = false;
        const startDrag = (e, type) => { if(type==='start') isDraggingStart=true; else isDraggingEnd=true; e.preventDefault(); };
        if(cropHandleStart) {
            cropHandleStart.addEventListener('mousedown', (e) => startDrag(e, 'start')); cropHandleStart.addEventListener('touchstart', (e) => startDrag(e, 'start'));
        }
        if(cropHandleEnd) {
            cropHandleEnd.addEventListener('mousedown', (e) => startDrag(e, 'end')); cropHandleEnd.addEventListener('touchstart', (e) => startDrag(e, 'end'));
        }
        
        const handleMove = (clientX) => {
            if (!playerAudioBuffer) return;
            const rect = waveformContainer.getBoundingClientRect();
            let x = Math.max(0, Math.min(clientX - rect.left, rect.width));
            const time = (x / rect.width) * playerAudioBuffer.duration;
            if (isDraggingStart) cropStartTime = Math.min(time, cropEndTime - 0.1);
            else if (isDraggingEnd) cropEndTime = Math.max(time, cropStartTime + 0.1);
            updateCropUI();
        };
        const handleUp = () => { isDraggingStart = false; isDraggingEnd = false; };
        document.addEventListener('mousemove', (e) => { if (isDraggingStart || isDraggingEnd) handleMove(e.clientX); });
        document.addEventListener('touchmove', (e) => { if (isDraggingStart || isDraggingEnd) handleMove(e.touches[0].clientX); }, {passive: false});
        document.addEventListener('mouseup', handleUp); document.addEventListener('touchend', handleUp);

        if(waveformContainer) waveformContainer.addEventListener('click', (e) => {
            if (isDraggingStart || isDraggingEnd || !playerAudioBuffer) return;
            const x = e.clientX - waveformContainer.getBoundingClientRect().left;
            const clickTime = (x / waveformContainer.clientWidth) * playerAudioBuffer.duration;
            if (clickTime >= cropStartTime && clickTime <= cropEndTime) { stopPlayback(); startPlayback(clickTime); }
        });

        if(btnPlayerPlay) btnPlayerPlay.addEventListener('click', () => { if (!playerAudioBuffer || !audioContext) return; if (isPlayingRecorded) stopPlayback(); else startPlayback(cropStartTime); });

        function startPlayback(startTime) {
            if (!micStream) setAudioSessionType('playback');
            audioPlayerSource = audioContext.createBufferSource(); audioPlayerSource.buffer = playerAudioBuffer;
            audioPlayerSource.connect(analyser); audioPlayerSource.connect(audioContext.destination);
            audioPlayerSource.onended = () => stopPlayback();
            const duration = cropEndTime - startTime;
            if (duration > 0) { audioPlayerSource.start(0, startTime, duration); playbackStartTime = audioContext.currentTime; playStartOffset = startTime; }
            isPlayingRecorded = true; btnPlayerPlay.innerHTML = '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/></svg>';
        }

        function stopPlayback() {
            if (audioPlayerSource) { try { audioPlayerSource.stop(); } catch(e){} try { audioPlayerSource.disconnect(); } catch(e){} audioPlayerSource = null; }
            isPlayingRecorded = false; btnPlayerPlay.innerHTML = '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
            if (playerAudioBuffer) drawWaveform(playerAudioBuffer.getChannelData(0));
        }

        function drawWaveform(data) {
            const ctx = waveformCanvas.getContext('2d');
            const w = waveformCanvas.clientWidth, h = waveformCanvas.clientHeight;
            if (waveformCanvas.width !== w) waveformCanvas.width = w; if (waveformCanvas.height !== h) waveformCanvas.height = h;
            ctx.clearRect(0, 0, w, h);
            
            // Get CSS colors
            const computedStyle = getComputedStyle(document.documentElement);
            const colorSharp = computedStyle.getPropertyValue('--stat-line-sharp').trim() || '#ef4444';
            const colorFlat = computedStyle.getPropertyValue('--color-flat-blue').trim() || '#3b82f6';
            const colorPerfect = computedStyle.getPropertyValue('--stat-line-perfect').trim() || '#10b981';
            const colorDefault = computedStyle.getPropertyValue('--accent').trim() || '#8b5cf6';

            const step = Math.ceil(data.length / w), amp = h / 2;
            const duration = playerAudioBuffer ? playerAudioBuffer.duration : 0;
            const pitchDataStep = currentRecordingPitchData ? (duration / currentRecordingPitchData.length) : 0;

            for (let i = 0; i < w; i++) {
                let min = 1.0, max = -1.0;
                for (let j = 0; j < step; j++) { const datum = data[(i * step) + j]; if (datum < min) min = datum; if (datum > max) max = datum; }
                
                // Color Logic
                let strokeColor = colorDefault;
                if (currentRecordingPitchData && duration > 0) {
                    const time = (i / w) * duration;
                    const index = Math.floor(time / pitchDataStep);
                    if (index >= 0 && index < currentRecordingPitchData.length) {
                        const p = currentRecordingPitchData[index];
                        if (p && p.cents !== null) {
                            if (p.cents > 5) strokeColor = colorSharp;
                            else if (p.cents < -5) strokeColor = colorFlat;
                            else strokeColor = colorPerfect;
                        }
                    }
                }

                ctx.strokeStyle = strokeColor;
                ctx.beginPath();
                ctx.moveTo(i, (1 + min) * amp); 
                ctx.lineTo(i, (1 + max) * amp);
                ctx.stroke();
            }
            
            if (isPlayingRecorded) {
                const percent = (audioContext.currentTime - playbackStartTime + playStartOffset) / playerAudioBuffer.duration;
                if (percent >= 0 && percent <= 1) { ctx.fillStyle = '#ef4444'; ctx.fillRect(percent * w, 0, 2, h); }
            }
        }

        if(btnPlayerClose) btnPlayerClose.addEventListener('click', async () => { 
            stopPlayback(); 
            recordedBlob = null; 
            playerAudioBuffer = null; 
            currentRecordingPitchData = null;
            
            // Reset Stats to Live mode
            noteStats = {};
            isStatsPaused = false;
            if(btnPauseStats) {
                btnPauseStats.classList.remove('paused');
                btnPauseStats.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M6 4h4v16H6zm8 0h4v16h-4z"/></svg>';
            }
            refreshStatsUI(true);

            playerContainer.style.display = 'none'; 
            controlsContainer.style.display = 'flex'; 
            await startMic(); 
        });

        // Share & Metronome Logic (Simplified for brevity but functional)
        if(btnPlayerShare) btnPlayerShare.addEventListener('click', () => { if (!playerAudioBuffer) return; filenameInput.value = `BombardeLab_${new Date().toISOString().slice(0,19).replace(/[:T]/g,'-')}`; renameModal.style.display = 'flex'; });
        if(renameCancelBtn) renameCancelBtn.addEventListener('click', () => renameModal.style.display = 'none');
        if(renameShareBtn) renameShareBtn.addEventListener('click', async () => {
            renameModal.style.display = 'none';
            const duration = cropEndTime - cropStartTime, sr = playerAudioBuffer.sampleRate;
            const offlineCtx = new OfflineAudioContext(playerAudioBuffer.numberOfChannels, sr * duration, sr);
            const source = offlineCtx.createBufferSource(); source.buffer = playerAudioBuffer;
            source.connect(offlineCtx.destination); source.start(0, cropStartTime, duration);
            const renderedBuffer = await offlineCtx.startRendering();
            
            // WAV Encoder
            const len = renderedBuffer.length * renderedBuffer.numberOfChannels * 2 + 44, buf = new ArrayBuffer(len), view = new DataView(buf);
            const setU32 = (o,v) => view.setUint32(o,v,true), setU16 = (o,v) => view.setUint16(o,v,true);
            const writeString = (o,s) => { for(let i=0;i<s.length;i++) view.setUint8(o+i, s.charCodeAt(i)); };
            writeString(0,"RIFF"); setU32(4, len-8); writeString(8,"WAVE"); writeString(12,"fmt "); setU32(16,16); setU16(20,1);
            setU16(22, renderedBuffer.numberOfChannels); setU32(24, sr); setU32(28, sr * 2 * renderedBuffer.numberOfChannels); setU16(32, renderedBuffer.numberOfChannels * 2); setU16(34,16); writeString(36,"data"); setU32(40, len-44);
            
            let offset = 44;
            const chans = []; for(let i=0; i<renderedBuffer.numberOfChannels; i++) chans.push(renderedBuffer.getChannelData(i));
            for(let i=0; i<renderedBuffer.length; i++) {
                for(let ch=0; ch<renderedBuffer.numberOfChannels; ch++) {
                    let s = Math.max(-1, Math.min(1, chans[ch][i]));
                    s = s < 0 ? s * 0x8000 : s * 0x7FFF;
                    view.setInt16(offset, s, true); offset += 2;
                }
            }
            const blob = new Blob([buf], {type: "audio/wav"});
            const filename = filenameInput.value.trim() || 'enregistrement';
            const file = new File([blob], `${filename}.wav`, { type: 'audio/wav' });

            if (navigator.canShare && navigator.canShare({ files: [file] })) {
                try { await navigator.share({ files: [file], title: filename }); } catch (e) {}
            } else {
                const a = document.createElement('a'); a.style.display = 'none'; a.href = URL.createObjectURL(blob);
                a.download = `${filename}.wav`; document.body.appendChild(a); a.click();
                setTimeout(() => { document.body.removeChild(a); URL.revokeObjectURL(a.href); }, 100);
            }
        });

        // Metronome listeners
        openMetroBtn.addEventListener('click', (e) => { e.stopPropagation(); metroModal.style.display = 'flex'; });
        btnMetroClose.addEventListener('click', () => metroModal.style.display = 'none');
        document.querySelectorAll('.metro-opt-btn').forEach(btn => btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            if (btn.dataset.mode === 'audio') metroModes.audio = !metroModes.audio;
            if (btn.dataset.mode === 'visual') metroModes.visual = !metroModes.visual;
            if (btn.dataset.mode === 'flash') {
                metroModes.flash = !metroModes.flash;
                if(metroModes.flash) enableFlash();
            }
            saveSettings();
        }));
        async function enableFlash() {
             try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                const track = stream.getVideoTracks()[0];
                const capabilities = track.getCapabilities();
                if (capabilities.torch) cameraTrack = track;
            } catch(e) {}
        }
        btnMetroMinus.addEventListener('click', () => { metroBpm = Math.max(30, metroBpm - 5); bpmDisplay.textContent = metroBpm; saveSettings(); });
        btnMetroPlus.addEventListener('click', () => { metroBpm = Math.min(250, metroBpm + 5); bpmDisplay.textContent = metroBpm; saveSettings(); });
        btnMetroTap.addEventListener('click', () => {
            const now = Date.now(); if (tapTimes.length > 0 && now - tapTimes[tapTimes.length - 1] > 2000) tapTimes = [];
            tapTimes.push(now); if (tapTimes.length > 8) tapTimes.shift();
            if (tapTimes.length > 1) {
                let sum = 0; for (let i = 1; i < tapTimes.length; i++) sum += tapTimes[i] - tapTimes[i-1];
                metroBpm = Math.round(60000 / (sum / (tapTimes.length - 1)));
                if (metroBpm >= 30 && metroBpm <= 250 && !bpmDisplay.querySelector('input')) bpmDisplay.textContent = metroBpm;
                saveSettings();
            }
            btnMetroTap.style.background = "var(--accent)"; btnMetroTap.style.color = "white"; setTimeout(() => { btnMetroTap.style.background = ""; btnMetroTap.style.color = ""; }, 100);
        });
        bpmDisplay.addEventListener('click', () => {
            if (bpmDisplay.querySelector('input')) return;
            const input = document.createElement('input'); input.type = 'number'; input.value = metroBpm; input.className = 'bpm-input-edit';
            bpmDisplay.innerHTML = ''; bpmDisplay.appendChild(input); input.focus();
            const finalize = () => { metroBpm = Math.max(30, Math.min(250, parseInt(input.value)||metroBpm)); bpmDisplay.textContent = metroBpm; saveSettings(); };
            input.addEventListener('blur', finalize); input.addEventListener('keypress', (e) => { if (e.key === 'Enter') finalize(); });
            input.addEventListener('click', (e) => e.stopPropagation());
        });
        
        // Clic du métronome joué à l'instant t. L'enveloppe est entièrement calée sur t :
        // avant, la décroissance démarrait au moment de la programmation (jusqu'à 100 ms plus tôt)
        // et le clic était déjà très atténué quand il sonnait.
        // Onde carrée vers 1,6 kHz : plus d'énergie et des harmoniques bien rendues par un haut-parleur de téléphone.
        function playMetroClick(t) {
            const osc = audioContext.createOscillator(); const g = audioContext.createGain();
            osc.type = 'square'; osc.frequency.value = 1600;
            g.gain.setValueAtTime(0, t);
            g.gain.linearRampToValueAtTime(0.9, t + 0.002);
            g.gain.setValueAtTime(0.9, t + 0.02);
            g.gain.exponentialRampToValueAtTime(0.001, t + 0.09);
            osc.connect(g); g.connect(audioContext.destination);
            osc.start(t); osc.stop(t + 0.1);
        }

        function metroScheduler() {
            while (metroNextNoteTime < audioContext.currentTime + 0.1) {
                if (metroModes.audio) playMetroClick(metroNextNoteTime);
                if (metroModes.visual) {
                    setTimeout(() => { document.body.classList.add('metro-flash'); setTimeout(() => document.body.classList.remove('metro-flash'), 150); }, Math.max(0, (metroNextNoteTime - audioContext.currentTime) * 1000));
                }
                if (metroModes.flash && cameraTrack) {
                    setTimeout(() => {
                         cameraTrack.applyConstraints({ advanced: [{ torch: true }] }).catch(()=>{});
                         setTimeout(() => { cameraTrack.applyConstraints({ advanced: [{ torch: false }] }).catch(()=>{}); }, 100);
                    }, Math.max(0, (metroNextNoteTime - audioContext.currentTime) * 1000));
                }
                metroNextNoteTime += 60.0 / metroBpm;
            }
            metroTimerID = setTimeout(metroScheduler, 25);
        }

        btnMetroPlay.addEventListener('click', () => {
            if (!audioContext) initAudio(); if (audioContext.state === 'suspended') audioContext.resume();
            metroIsPlaying = !metroIsPlaying;
            if (metroIsPlaying) {
                btnMetroPlay.innerHTML = '<svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor"><rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/></svg>';
                btnMetroPlay.classList.add('playing'); metroNextNoteTime = audioContext.currentTime + 0.05; metroScheduler();
            } else {
                btnMetroPlay.innerHTML = '<svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
                btnMetroPlay.classList.remove('playing'); clearTimeout(metroTimerID);
            }
        });

        // Other listeners
        settingsBtn.addEventListener('click', (e) => { e.stopPropagation(); openPanel(document.getElementById('settings-panel')); });
        helpBtn.addEventListener('click', (e) => { e.stopPropagation(); openPanel(document.getElementById('help-panel')); });
        panelOverlay.addEventListener('click', closeAllPanels);
        closePanelBtns.forEach(btn => btn.addEventListener('click', closeAllPanels));
        gateSlider.addEventListener('input', (e) => { noiseGateThreshold = parseFloat(e.target.value); updateGateDisplay(noiseGateThreshold); saveSettings(); });
        document.getElementById('default-pitch-input').addEventListener('change', (e) => { let val = parseFloat(e.target.value); if (!isNaN(val)) { referencePitch = val; updateRefUI(); saveSettings(); }});
        document.querySelectorAll('#default-temp-switcher .theme-opt').forEach(opt => {
            opt.addEventListener('click', () => {
                const mode = opt.dataset.val;
                if (mode === 'equal') { setTemperamentMode(mode); updateOptionUI('#default-temp-switcher', mode); saveSettings(); }
                else { pendingTemperamentMode = mode; isEditingDefaultSettings = true; document.getElementById('key-modal-title').innerText = `${t(mode==='bagad'?'temp_bagad':'temp_just').split('–')[0]} (${t('btn_auto')==='Auto'?'Défaut':'Default'})`; document.getElementById('key-selection-modal').style.display = 'flex'; }
            });
        });
        document.getElementById('key-cancel-btn').addEventListener('click', () => { document.getElementById('key-selection-modal').style.display = 'none'; isEditingDefaultSettings = false; });
        document.querySelectorAll('#active-temp-switcher .theme-opt').forEach(opt => {
            opt.addEventListener('click', () => {
                const mode = opt.dataset.val;
                if (mode === temperamentMode && mode !== 'just' && mode !== 'bagad') return;

                const applyMode = () => {
                    if (mode === 'equal' || mode === 'pipeband') {
                        setTemperamentMode(mode);
                        updateOptionUI('#active-temp-switcher', mode);
                        refreshStatsUI(false);
                    } else {
                        pendingTemperamentMode = mode;
                        isEditingDefaultSettings = false; 
                        document.getElementById('key-modal-title').innerText = t(mode==='bagad'?'temp_bagad':'temp_just').split('–')[0];
                        document.getElementById('key-selection-modal').style.display = 'flex';
                    }
                };

                if ((temperamentMode === 'just' || temperamentMode === 'bagad') && mode !== temperamentMode && (mode === 'equal' || mode === 'pipeband')) {
                    onConfirmAction = () => { applyMode(); };
                    confirmModal.style.display = 'flex';
                } else {
                    applyMode();
                }
            });
        });
        strobeToggle.addEventListener('click', () => { strobeEnabled = !strobeEnabled; if (strobeEnabled) { strobeToggle.classList.add('on'); strobeCanvas.style.display = 'block'; } else { strobeToggle.classList.remove('on'); strobeCanvas.style.display = 'none'; if(isStrobeMode){isStrobeMode=false; tunerContainerDiv.classList.remove('strobe-mode');} } saveSettings(); });
        strobeSmoothSlider.addEventListener('input', () => { updateStrobeSmoothing(); saveSettings(); });
        document.getElementById('reset-strobe-smoothing').addEventListener('click', () => { strobeSmoothSlider.value = 80; updateStrobeSmoothing(); saveSettings(); });
        document.getElementById('reset-gate').addEventListener('click', () => { noiseGateThreshold = 0.01; gateSlider.value = 0.01; updateGateDisplay(0.01); saveSettings(); });
        document.getElementById('reset-reset-delay').addEventListener('click', () => { resetButtonDelay = 0; resetDelaySlider.value = 0; resetDelayVal.innerText = "0s"; saveSettings(); });
        document.getElementById('reset-countdown').addEventListener('click', () => { countdownTime = 3; countdownSlider.value = 3; countdownVal.innerText = "3s"; saveSettings(); });
        document.getElementById('reset-calib-duration').addEventListener('click', () => { calibrationDuration = 3; calibSlider.value = 3; calibVal.innerText = "3s"; saveSettings(); });
        tunerContainerDiv.addEventListener('click', () => { if (!strobeEnabled) return; isStrobeMode = !isStrobeMode; if (isStrobeMode) tunerContainerDiv.classList.add('strobe-mode'); else tunerContainerDiv.classList.remove('strobe-mode'); });
        countdownSlider.addEventListener('input', (e) => { countdownTime = parseInt(e.target.value, 10); countdownVal.innerText = countdownTime + 's'; saveSettings(); });
        calibSlider.addEventListener('input', (e) => { calibrationDuration = parseFloat(e.target.value); calibVal.innerText = calibrationDuration + 's'; saveSettings(); });
        resetDelaySlider.addEventListener('input', (e) => { const idx = parseInt(e.target.value, 10); resetButtonDelay = resetDelayMap[idx]; resetDelayVal.innerText = resetButtonDelay + 's'; saveSettings(); });
        themeOpts.forEach(opt => { opt.addEventListener('click', () => { setTheme(opt.dataset.val); saveSettings(); }); });
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            const activeThemeEl = document.querySelector('#theme-switcher-main .theme-opt.active');
            if (activeThemeEl && activeThemeEl.dataset.val === 'system') { document.body.setAttribute('data-theme', e.matches ? 'dark' : 'light'); if (Object.keys(noteStats).length > 0) setTimeout(drawStatsGraph, 100); }
        });
        btnStatsMode.addEventListener('click', () => { isStatsAutoMode = !isStatsAutoMode; updateStatsModeUI(); });
        btnPauseStats.addEventListener('click', () => {
            if (isStatsPaused) { isStatsPaused = false; btnPauseStats.classList.remove('paused'); btnPauseStats.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M6 4h4v16H6zm8 0h4v16h-4z"/></svg>'; refreshStatsUI(true); }
            else { isStatsPaused = true; btnPauseStats.classList.add('paused'); btnPauseStats.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>'; }
        });

        confirmNoBtn.addEventListener('click', () => confirmModal.style.display = 'none');
        confirmYesBtn.addEventListener('click', () => { confirmModal.style.display = 'none'; if (onConfirmAction) { onConfirmAction(); onConfirmAction = null; } });
        document.getElementById('btn-select-equal').addEventListener('click', () => { setTemperamentMode('equal'); modalTempOverlay.style.display = 'none'; refreshStatsUI(false); saveSettings(); });
        document.getElementById('btn-select-just').addEventListener('click', () => { pendingTemperamentMode = 'just'; modalTempOverlay.style.display = 'none'; document.getElementById('key-modal-title').innerText = t('temp_just').split('–')[0]; document.getElementById('key-selection-modal').style.display = 'flex'; });
        document.getElementById('btn-select-bagad').addEventListener('click', () => { pendingTemperamentMode = 'bagad'; modalTempOverlay.style.display = 'none'; document.getElementById('key-modal-title').innerText = t('temp_bagad').split('–')[0]; document.getElementById('key-selection-modal').style.display = 'flex'; });
        document.getElementById('btn-select-pipeband').addEventListener('click', () => { setTemperamentMode('pipeband'); modalTempOverlay.style.display = 'none'; refreshStatsUI(false); saveSettings(); });
        document.getElementById('temp-close-btn').addEventListener('click', () => modalTempOverlay.style.display = 'none');
        hzValDisplay.addEventListener('click', () => { pitchInput.value = referencePitch; document.getElementById('pitch-input-modal').style.display = 'flex'; pitchInput.focus(); pitchInput.select(); });
        pitchCancelBtn.addEventListener('click', closePitchModal);
        pitchOkBtn.addEventListener('click', () => {
            let val = parseFloat(pitchInput.value);
            if (!isNaN(val) && val > 0) { val = Math.max(400, Math.min(480, val)); referencePitch = val; updateRefUI(); saveSettings(); refreshStatsUI(false); 
            const msg = document.getElementById('calibration-msg'); msg.innerText = `${t('msg_pitch_set')} ${referencePitch.toFixed(1)} Hz`; msg.style.color = "var(--accent)"; setTimeout(() => { msg.innerText = ""; }, 3000); }
            closePitchModal();
        });
        pitchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') pitchOkBtn.click(); });
        btnCalibrate.addEventListener('click', handleCalibrationClick);
        btnResetStats.addEventListener('click', () => {
            if (resetButtonDelay === 0) { noteStats = {}; refreshStatsUI(true); } else {
                if (resetTimeoutId) return;
                let timeLeft = resetButtonDelay; const originalText = t('btn_reset'); btnResetStats.disabled = true; btnResetStats.innerText = `${t('btn_reset_countdown')} ${timeLeft}s...`;
                const intv = setInterval(() => {
                    timeLeft--; if (timeLeft <= 0) { clearInterval(intv); noteStats = {}; refreshStatsUI(true); btnResetStats.innerText = originalText; btnResetStats.disabled = false; resetTimeoutId = null; } else { btnResetStats.innerText = `${t('btn_reset_countdown')} ${timeLeft}s...`; }
                }, 1000); resetTimeoutId = intv;
            }
        });
        btnMinus.addEventListener('click', () => { referencePitch = Math.round((referencePitch - 0.1) * 10) / 10; updateRefUI(); saveSettings(); refreshStatsUI(false); });
        btnPlus.addEventListener('click', () => { referencePitch = Math.round((referencePitch + 0.1) * 10) / 10; updateRefUI(); saveSettings(); refreshStatsUI(false); });
        statsGraph.addEventListener('click', (e) => {
            if (Object.keys(noteStats).length === 0) return;
            const x = e.clientX - statsGraph.getBoundingClientRect().left; let found = null;
            for (let zone of graphClickZones) if (x >= zone.x - 10 && x <= zone.x + zone.w + 10) { found = zone.id; break; }
            if (found) openStatPopup(found); else statPopup.classList.remove('visible');
        });
        document.addEventListener('click', (e) => { if (!e.target.closest('#stats-graph') && !e.target.closest('.stat-popup')) statPopup.classList.remove('visible'); });

startBtn.addEventListener('click', async () => {
    const errorMsgEl = document.getElementById('start-error');
    errorMsgEl.innerText = ""; 
    startBtn.innerText = "Activation..."; // Indique que le clic a bien été reçu
    
    try { 
        await startMic(); 
        document.getElementById('start-overlay').style.display = 'none'; 
        resizeCanvas(); 
        startAudioProcessing(); 
        isRunning = true; 
        requestWakeLock();
    } 
    catch (e) { 
        console.error("Erreur", e); 
        startBtn.innerText = "Réessayer"; // Change le texte du bouton
        
        // AFFICHE L'ERREUR EXACTE À L'ÉCRAN
        let msg = "Erreur inconnue";
        if (e.name === 'NotAllowedError' || e.name === 'PermissionDeniedError') {
            msg = "Permission Micro Refusée via les réglages.";
        } else if (e.name === 'NotFoundError') {
            msg = "Aucun microphone trouvé.";
        } else if (e.name === 'NotSupportedError') {
            msg = "Contexte sécurisé (HTTPS) requis.";
        } else {
            msg = e.message || e.name; // Affiche le message technique brut
        }
        
        errorMsgEl.innerText = msg;
        errorMsgEl.style.color = "red";
        errorMsgEl.style.fontWeight = "bold";
    }
});
        
        // Initial calls
        loadSettings();
        updateRefUI();
        checkEmptyStats();
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        updateStrobeSmoothing();
        updateStatsModeUI();
        populateKeySelectionModal();
        updateTemperamentInfo();
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js').catch(err => console.warn('Service worker non enregistré :', err));
            });
        }
    </script>
</body>
</html>