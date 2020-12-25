{"version":3,"sources":["pin-panel.js"],"names":["BX","namespace","Grid","PinPanel","parent","this","panel","isSelected","offset","animationDuration","pinned","init","prototype","getPanel","bindOnRowsEvents","addCustomEvent","delegate","_onThereSelectedRows","_onNoSelectedRows","bindOnWindowEvents","bind","window","proxy","_onResize","document","addEventListener","_onScroll","Utils","listenerParams","passive","unbindOnWindowEvents","unbind","removeEventListener","getActionsPanel","getScrollBottom","scrollTop","getWindowHeight","getPanelRect","type","isPlainObject","panelRect","pos","getPanelPrevBottom","prev","previousSibling","bottom","parseFloat","style","windowHeight","height","pinPanel","withAnimation","width","parentNode","bodyRect","getBody","getStartDiffPanelPosition","setProperty","classList","add","removeProperty","requestAnimationFrame","isNeedPinAbsolute","absolutePin","top","adjustPanelPosition","unpinPanel","parentRect","Math","abs","translateOffset","delay","cb","setTimeout","remove","isSelectedRows","isNeedPin","scrollX","pageXOffset","lastScrollX","panelPos","left","pinController","isPinned","getEndDiffPanelPosition","prevPanelPos","scrollBottom","diff","prevPanelBottom","lastIsSelected","getBoundingClientRect"],"mappings":"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","file":"pin-panel.map.js"}