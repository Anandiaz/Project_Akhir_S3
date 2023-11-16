var tombol = document.getElementById('toggle-mode');
var dark = document.getElementById('dark');
var light = document.getElementById('light');
tombol.addEventListener("click", () => {
    document.body.classList.toggle("light-mode");
    if(document.body.classList.contains("light-mode")){
        light.style.display = 'none';
        dark.style.display = 'block';
    } else {
        light.style.display = 'block';
        dark.style.display = 'none';
    }
});

const title = document.getElementById('hero-title');
title.style.color = '#FBA1B7';