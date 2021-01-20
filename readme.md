# MOGA Theme Customizer

change and preview you colors, fonts and other css from admin panel.  
No PhD in Informatics needed.


## Installation
1) Moga-Theme installieren, falls noch nicht geschehen:  
  `composer require -n moga-kit/moga-theme`
  
2) Installiere Source Dateien und Bibliotheken im Ordner "source/Application/views/moga/":  
   `npm install`
    
3) Moga-Modul installieren:  
  `composer require -n moga-kit/moga-module`
  
3) ~~Nennen die Datei "source/out/moga/src/scss/**custom.dist.scss**"  
  in "source/out/moga/src/scss/**custom.scss** um~~  
  `mv source/out/moga/src/scss/custom.dist.scss source/out/moga/src/scss/custom.scss`
  
4) Mache die Datei "source/**Application/views**/moga/build/scss/_custom_styles.scss" beschreibbar:  
  `chmod 777 source/Application/views/moga/src/scss/_custom_styles.scss`
   
5) Mache die Datei "source/**Application/views**/moga/build/scss/_custom_variables.scss" beschreibbar:  
  `chmod 777 source/Application/views/moga/src/scss/_custom_variables.scss`
   
6) Mache die Datei "source/**out**/moga/src/css/styles.min.css" beschreibbar:  
  `chmod 777 source/out/moga/src/css/styles.min.css`
   
7) Mache die Datei "source/**out**/moga/src/css/preview.css" beschreibbar:  
  `chmod 777 source/out/moga/src/css/preview.css`   