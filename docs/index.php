<!DOCTYPE html>
<html lang="en">

<head>
  <title>Runcloud Agency API v3 Docs</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div id="app">
    <div class="container-fluid p-5 bg-primary text-white text-center">
      <h1>Runcloud Agency API v3 SDK Docs</h1>
    </div>
    <div class="container mt-5">
      <div class="row">
        <div class="d-flex align-items-center p-0">
          <h5 class="p-0 m-0">Base URL</h5>
          <button class="ms-2 btn btn-light btn-copy" data-clipboard-target="#base_url_text" title="Copy to clipboard"><i class="fa-regular fa-clipboard"></i></button>
        </div>
        <p class="p-0" id="base_url_text"><code>{{ baseUrl }}</code></p>
        <div class="d-flex align-items-center p-0">
          <h5 class="p-0 m-0">How to use ?</h5>
          <button class="ms-2 btn btn-light btn-copy" data-clipboard-target="#usage_example" title="Copy to clipboard"><i class="fa-regular fa-clipboard"></i></button>
        </div>
        <p class="p-0" id="usage_example">
          <code>
            $client = new \RunCloudIO\PHPApiSDK\Client();
            <br>
            $client->setToken('<strong>Your agency API token</strong>')->send('<strong>agency-api</strong>' OR '<strong>core-api</strong>', '<strong>Action Name</strong>', [<strong>URL Parameters</strong>], [<strong>Form Data</strong>]);
          </code>
        </p>
        <div class="p-0"><a href="/playground.php" class="btn btn-link">Try Demo</a></div>
        <div class="card p-0 mt-3" id="agency_api">
          <div class="card-header">Agency API</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <th>Action Name</th>
                  <th>Description</th>
                  <th class="text-center">Method</th>
                  <th>Path</th>
                </thead>
                <tbody>
                  <tr v-for="(api, index) in agencyApiData" :key="index">
                    <td><span :id="api.name"><code>{{ api.name }}</code></span><button class="ms-2 btn btn-light btn-sm btn-copy" :data-clipboard-target="`#${api.name}`" title="Copy to clipboard"><i class="fa-regular fa-clipboard"></i></button></td>
                    <td>{{ api.description }}</td>
                    <td class="text-center"><code>{{ api.method }}</code></td>
                    <td v-html="api.path"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card p-0 mt-3" id="core_api">
          <div class="card-header">Core API</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <th>Action Name</th>
                  <th>Description</th>
                  <th class="text-center">Method</th>
                  <th>Path</th>
                </thead>
                <tbody>
                  <tr v-for="(api, index) in coreApiData" :key="index">
                    <td><span :id="api.name"><code>{{ api.name }}</code></span><button class="ms-2 btn btn-light btn-sm btn-copy" :data-clipboard-target="`#${api.name}`" title="Copy to clipboard"><i class="fa-regular fa-clipboard"></i></button></td>
                    <td>{{ api.description }}</td>
                    <td class="text-center"><code>{{ api.method }}</code></td>
                    <td v-html="api.path"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="module">
    new ClipboardJS('.btn-copy');
    import {
      createApp,
      toRaw,
      isProxy
    } from 'https://cdnjs.cloudflare.com/ajax/libs/vue/3.2.47/vue.esm-browser.prod.min.js'

    createApp({
      data() {
        return {
          coreApis: <?php $config = require("../src/config.php");
                    echo json_encode($config['core-api']);  ?>,
          agencyApis: <?php $config = require("../src/config.php");
                      echo json_encode($config['agency-api']);  ?>,
          baseUrl: <?php $config = require("../src/config.php");
                    echo json_encode($config['domain'] . $config['base']);  ?>
        }
      },
      computed: {
        agencyApiData() {
          const collections = toRaw(this.agencyApis);
          return Object.keys(collections).map(obj => {
            return {
              name: obj,
              description: collections[obj][2],
              method: collections[obj][0],
              path: collections[obj][1].replace(new RegExp("%s", "g"), "<code>%s</code>")
            }
          })
        },

        coreApiData() {
          const collections = toRaw(this.coreApis);
          return Object.keys(collections).map(obj => {
            return {
              name: obj,
              description: collections[obj][2],
              method: collections[obj][0],
              path: collections[obj][1].replace(new RegExp("%s", "g"), "<code>%s</code>")
            }
          })
        },


      },
    }).mount('#app')
  </script>
</body>

</html>