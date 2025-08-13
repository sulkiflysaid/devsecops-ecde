<?php ?>
<!-- amCharts 5 libs -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<div id="chartdiv" style="width: 100%; height: 600px;"></div>

<script>
const json = {
  "data": [
    {"kode_prodi":1,"prodi":"EKONOMI PEMBANGUNAN","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":842,"jumlah_dosen":40,"ratio":0.047505938242280284},
    {"kode_prodi":127,"prodi":"ILMU MANAJEMEN","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":53,"jumlah_dosen":9,"ratio":0.16981132075471697},
    {"kode_prodi":6,"prodi":"AKUNTANSI","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":1327,"jumlah_dosen":45,"ratio":0.03391107761868877},
    {"kode_prodi":4,"prodi":"MANAJEMEN","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":1132,"jumlah_dosen":58,"ratio":0.05123674911660778},
    {"kode_prodi":129,"prodi":"ILMU EKONOMI","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":86,"jumlah_dosen":13,"ratio":0.1511627906976744},
    {"kode_prodi":125,"prodi":"MANAJEMEN","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":241,"jumlah_dosen":22,"ratio":0.0912863070539419},
    {"kode_prodi":3,"prodi":"EKONOMI SYARIAH","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":478,"jumlah_dosen":31,"ratio":0.06485355648535565},
    {"kode_prodi":72,"prodi":"PENDIDIKAN PROFESI AKUNTANSI","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":0,"jumlah_dosen":0,"ratio":0},
    {"kode_prodi":126,"prodi":"ILMU EKONOMI","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":33,"jumlah_dosen":8,"ratio":0.24242424242424243},
    {"kode_prodi":196,"prodi":"AKUNTANSI","kode_fakultas":1,"fakultas":"EKONOMI DAN BISNIS","jumlah_mahasiswa":49,"jumlah_dosen":8,"ratio":0.16326530612244897}
  ],
  "message": "Success"
};

am5.ready(function() {
  // Root
  var root = am5.Root.new("chartdiv");
  root.setThemes([ am5themes_Animated.new(root) ]);

  // Chart
  var chart = root.container.children.push(am5xy.XYChart.new(root, {
    panX: false, panY: false, wheelX: "none", wheelY: "none",
    layout: root.verticalLayout
  }));

  // Data (sort by ratio ascending so "better" 1:x is left)
  const data = [...json.data].sort((a,b) => a.ratio - b.ratio);

  // X axis (categories = prodi)
  var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
    categoryField: "prodi",
    renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 30 })
  }));
  xAxis.data.setAll(data);

  // Make labels readable
  xAxis.get("renderer").labels.template.setAll({
    rotation: -45,
    centerY: am5.p50,
    centerX: am5.p50,
    paddingTop: 10
  });

  // Left Y axis (counts)
  var yAxisCounts = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    renderer: am5xy.AxisRendererY.new(root, {}),
    extraMax: 0.05
  }));

  // Right Y axis (ratio)
  var yAxisRatio = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    renderer: am5xy.AxisRendererY.new(root, { opposite: true }),
    min: 0, extraMax: 0.1,
    numberFormat: "#,##0.00%"
  }));

  // Helper: format 1:x from ratio
  function ratioHuman(r) {
    if (!r) return "—";
    const x = Math.round(1 / r);
    return `1:${x}`;
  }

  // Column series - Mahasiswa
  var mahasiswaSeries = chart.series.push(am5xy.ColumnSeries.new(root, {
    name: "Mahasiswa",
    xAxis: xAxis,
    yAxis: yAxisCounts,
    categoryXField: "prodi",
    valueYField: "jumlah_mahasiswa",
    tooltip: am5.Tooltip.new(root, {
      labelText: "{name}\n{categoryX}\nMahasiswa: {valueY.formatNumber('#,###')}"
    })
  }));
  mahasiswaSeries.data.setAll(data);

  // Column series - Dosen
  var dosenSeries = chart.series.push(am5xy.ColumnSeries.new(root, {
    name: "Dosen",
    xAxis: xAxis,
    yAxis: yAxisCounts,
    categoryXField: "prodi",
    valueYField: "jumlah_dosen",
    clustered: true,
    tooltip: am5.Tooltip.new(root, {
      labelText: "{name}\n{categoryX}\nDosen: {valueY.formatNumber('#,###')}"
    })
  }));
  dosenSeries.data.setAll(data);

  // Line series - Ratio (right axis)
  var ratioSeries = chart.series.push(am5xy.LineSeries.new(root, {
    name: "Rasio Dosen/Mhs",
    xAxis: xAxis,
    yAxis: yAxisRatio,
    categoryXField: "prodi",
    valueYField: "ratio",
    tooltip: am5.Tooltip.new(root, {
      labelText: "{name}\n{categoryX}\nRatio: {valueY.formatNumber('#,##0.00%')} ({valueY.formatNumber('0.0000')} ≈ " + "{valueY}" + ")"
    })
  }));
  ratioSeries.strokes.template.setAll({ strokeWidth: 2 });
  ratioSeries.bullets.push(function() {
    return am5.Bullet.new(root, {
      sprite: am5.Circle.new(root, { radius: 4 })
    });
  });
  ratioSeries.data.setAll(data);

  // Improve ratio tooltip to show 1:x
  ratioSeries.adapters.add("tooltipText", function(_, target) {
    const di = target.dataItem;
    if (!di) return _;
    const r = di.get("valueY");
    return `Rasio Dosen/Mhs\n${di.get("categoryX")}\n${(r*100).toFixed(2)}%  (≈ ${ratioHuman(r)})`;
  });

  // Target lines on ratio axis (e.g., 1:30 and 1:20)
  function addRatioTarget(value, label) {
    const range = yAxisRatio.makeDataItem({
      value: value,
      endValue: value
    });
    yAxisRatio.createAxisRange(range);
    range.get("grid").setAll({ strokeOpacity: 0.5, strokeDasharray: [4,4] });
    range.get("label").setAll({
      text: label,
      isMeasured: false,
      centerX: am5.p100,
      x: am5.p100,
      background: am5.RoundedRectangle.new(root, { fillOpacity: 0.1 })
    });
  }
  addRatioTarget(1/30, "Target 1:30");
  addRatioTarget(1/20, "Target 1:20");

  // Legend
  var legend = chart.children.push(am5.Legend.new(root, { centerX: am5.p50, x: am5.p50 }));
  legend.data.setAll([mahasiswaSeries, dosenSeries, ratioSeries]);

  // Cursor
  chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none" }));

  // Animate in
  mahasiswaSeries.appear(600);
  dosenSeries.appear(600);
  ratioSeries.appear(600);
  chart.appear(600, 100);
});
</script>
