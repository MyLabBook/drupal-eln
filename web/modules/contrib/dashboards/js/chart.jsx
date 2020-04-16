import Chart from 'chart.js';
import interpolate from 'color-interpolate';
import 'mdn-polyfills/Node.prototype.before';
import 'mdn-polyfills/Array.prototype.includes';
const cmap = require('colormap');

(({ behaviors }, $$, $, { dashboards }) => {

  let colors = cmap({
    colormap: dashboards.colormap,
    format: 'rgb',
    alpha: dashboards.alpha,
    nshades: dashboards.shades,
  })

  behaviors.dashboardsChartTable = {
    attach(context) {
      const charts = $$(context, '[data-app=chart]:not(.processed)');
      charts.forEach((c) => {
        c.classList.add('processed');
        let config = {};
        for (let i = 0; i < c.attributes.length; i++) {
          if (c.attributes[i].nodeName.indexOf('data-chart') == 0) {
            config[c.attributes[i].nodeName] = c.attributes[i].nodeValue;
          }
        }
        this.init(c, c.getAttribute('data-chart-type'), config);
      })
    },
    getLabels(t) {
      const labels = [];
      $$(t, 'thead th').forEach((v) => {
        labels.push(v.textContent)
      })
      return labels;
    },
    getDataSingle(e) {
      const t = $(e, 'table');
      const dataset = {
        labels: [],
        datasets: []
      };
      let data = [];
      let colormap = interpolate(colors);
      $$(t, 'tbody tr td:first-child').forEach((v) => {
        dataset.labels.push(v.textContent);
      })
      $$(t, 'tbody tr').forEach(v => {
        $$(v, 'td:last-child').forEach(td => {
          data.push(td.textContent);
        })
      })

      let max =  Math.max.apply(Math, data);

      dataset.datasets.push({data: data, backgroundColor: [], label: $(t, 'th:last-child').textContent});
      dataset.datasets.forEach(d => {
        d.data.forEach(v => {
          if (max == 0) {
            d.backgroundColor.push(0);
            return;
          }
          const p = ((100/max)*v)/100;
          d.backgroundColor.push(colormap(p));
        })
      })
      return dataset;
    },
    getData(e, type) {
      const t = $(e, 'table');
      // if ($$(t,'th').length == 2) {
      //   return this.getDataSingle(e);
      // }
      const dataset = {
        labels: $$(t, 'tbody tr').map(v => $(v, 'td').textContent),
        datasets: []
      };
      let colormap = interpolate(colors);
      let max = 0;

      const rows = $$(t, 'th').slice(1).map(v => v.textContent);
      rows.forEach((v,i) => {
        dataset.datasets[i] = {
          data: [],
          label: v,
          backgroundColor: []
        }
      });
      $$(t, 'tbody tr').forEach((v, i) => {
        $$(v, 'td').slice(1).forEach((d, ii) => {
          let parsed = parseInt(d.textContent);
          if (!isNaN(parsed)) {
            dataset.datasets[ii].data.push(parsed);
            if (parsed > max) {
              max = parsed;
            }
          } else {
            dataset.datasets[ii].data.push(d.textContent);
          }
        })
      });

      dataset.datasets.forEach((v, i) => {
        dataset.datasets[i].backgroundColor = colormap(((100/dataset.datasets.length)*i)/100);
      })
      return dataset;
    },
    init(e, type, config) {
      const allowedBars = [
        'bar',
        'pie',
        'line',
        'radar',
        'doughnut',
        'polarArea',
        'bubble',
        'scatter',
      ];
      if (!type || !allowedBars.includes(type)) {
        type = 'bar';
      }
      $(e, 'div').before(document.createElement('canvas'))
      const canvas = $(e, 'canvas');
      const data = this.getData(e, type);
      e.chart = new Chart(canvas, {
        type: type,
        data: data,
        options: {
          tooltips: {
            mode: 'index',
            intersect: false
          },
          hover: {
            mode: 'index',
            intersect: false
          },
          legend: {
            display: (config['data-chart-display-legend']) ? true : false,
          }
        }
      });
      $(e, 'div').style.display = 'none';
      const dialog = Drupal.dialog($(e, 'table'), {
        width: '80%',
        height: '80%'
      });
      let modalLink = document.createElement('a');
      modalLink.innerHTML = Drupal.t('Show data');
      modalLink.classList.add('dashboard-table-link');
      modalLink.addEventListener('click', (e) => {
        dialog.show();
      });
      e.appendChild(modalLink);

    }
  }
})(Drupal, function(e,s) {
  return Array.prototype.slice.call(e.querySelectorAll(s));
}, function(e,s) {
  return e.querySelector(s);
}, drupalSettings)
