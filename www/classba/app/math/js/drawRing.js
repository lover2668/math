/**
 * Created by linxiao on 2017/5/16.
 */
function drawRing(opts) {
    var _opts = {
        parent: document.body,
        width: 100,
        radius: 45,
        arc: 5,
        perent: 100,
        color: ['#ccc', '#042b61'],
        textColor: '#000',
        textSize: '14px',
        animated: false,
        after: function() {}
    }, k;
    for (k in opts) _opts[k] = opts[k];

    var parent = _opts.parent,
        width = _opts.width,
        radius = _opts.radius,
        arc = _opts.arc,
        perent = parseFloat(_opts.perent),
        color = _opts.color,
        textSize = _opts.textSize,
        textColor = _opts.textColor,
        c = document.createElement('canvas'),
        ctx = null,
        x = 0,
        animated = _opts.animated,
        after = _opts.after;
    console.log(document.getElementById('xx-time-charts'));
    parent.append(c);
    ctx = c.getContext("2d");
    ctx.canvas.width = width;
    ctx.canvas.height = width;

    function clearFill() {
        ctx.clearRect(0, 0, width, width);
    }

    function fillBG() {
        ctx.beginPath();
        ctx.lineWidth = arc;
        ctx.strokeStyle = color[0];
        ctx.arc(width / 2, width / 2, radius, 0, 2 * Math.PI);
        ctx.stroke();
    }

    function fillArc(x) {
        ctx.beginPath();
        ctx.lineWidth = arc;
        ctx.strokeStyle = color[1];
        ctx.arc(width / 2, width / 2, radius, -90 * Math.PI / 180, (x * 3.6 - 90) * Math.PI / 180);
        ctx.stroke();
    }

    function fillText(x) {
        ctx.font = textSize + ' Arial';
        ctx.fillStyle = textColor;
        ctx.textBaseline = "middle";
        ctx.textAlign = 'center';
        ctx.fillText(x.toFixed(1) + '%', width / 2, width / 2);
    }

    function fill(x) {
        fillBG();
        fillArc(x);
        fillText(x);
    }

    if (!animated) return fill(perent);

    fill(x);
    !function animate() {
        if (++x > perent) return after && after();
        setTimeout(animate, 10);
        clearFill();
        fill(x);
    }();
}