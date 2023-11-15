document.addEventListener('DOMContentLoaded', function() {
        particleground(document.getElementById('particles'), {
            dotColor: '#378de5',
            lineColor: '#378de5'
        });
        var intro = document.getElementById('intro');
        intro.style.marginTop = -intro.offsetHeight / 2 + 'px';
    }, false);