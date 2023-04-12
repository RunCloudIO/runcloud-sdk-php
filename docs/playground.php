<!DOCTYPE html>
<html lang="en">

<head>
    <title>Runcloud Agency API v3 Docs | Demo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js" integrity="sha512-LUKzDoJKOLqnxGWWIBM4lzRBlxcva2ZTztO8bTcWPmDSpkErWx0bSP4pdsjNH8kiHAUPaT06UXcb+vOEZH+HpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        html,
        body {
            height: 100vh
        }

        #app,
        .app-wrapper {
            height: 100%;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="app-wrapper container-fluid p-5" style="height: 100%">
            <div class="row justify-content-center" style="width: 100%">
                <div class="col-sm-8">
                    <a href="/" class="btn btn-link">Go To Docs</a>
                </div>
                <div class="col-sm-8 mb-3" v-if="successMessage">
                    <div class="card">
                        <div class="card-header text-success d-flex"
                        style="justify-content: space-between; align-items: center">
                            <span>Successful</span>
                            <button class="btn btn-light btn-sm"><i class="fa-solid fa-xmark"
                            v-on:click="this.successMessage = ''"></i></button>
                        </div>
                        <div class="card-body">
                            <pre>{{ successMessage }}</pre>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8 mb-3" v-if="failedMessage">
                    <div class="card">
                    <div class="card-header text-danger d-flex"
                        style="justify-content: space-between; align-items: center"
                        v-on:click="this.failedMessage = ''">
                            <span>Unsuccessful</span>
                            <button class="btn btn-light btn-sm"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="card-body">
                            <pre>{{ failedMessage }}</pre>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            Demo
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Token</label>
                                    <input type="text" required class="form-control" v-model="formData.token" placeholder="Your API token..." />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <select class="form-control" v-model="formData.section" required>
                                        <option value="">Please choose...</option>
                                        <option value="agency-api">Agency API</option>
                                        <option value="core-api">Core API</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Action Name</label>
                                    <select class="form-control" required v-model="formData.action_name">
                                        <option value="">Please choose...</option>
                                        <option v-for="option in nameOptions" :key="option" :value="option">{{ option }}</option>
                                    </select>
                                </div>

                                <template v-if="formData.action_name">
                                    <div class="mb-3">
                                        <label class="form-label">Method</label>
                                        <p><code>{{ preview.method }}</code></p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">URL</label>
                                        <p><code>{{ preview.url }}</code></p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">URL Parameters</label>
                                        <textarea rows="1" class="form-control" placeholder="Enter parameter values here separated by commas..." v-model="formData.urlParams"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Form Data</label>
                                        <div class="row">
                                            <div class="col-sm-12 d-flex mb-3" v-for="(data, index) in Object.keys(formData.formData).map(obj => obj)" :key="index">
                                                <input type="text" disabled class="form-control" :value="data" />
                                                <input type="text" disabled class="form-control ms-2" :value="formData.formData[data]" />
                                                <button type="button" class="btn btn-danger ms-2" v-on:click="deleteNewParam(data)">Delete</button>
                                            </div>
                                            <div class="col-sm-12 d-flex">
                                                <input type="text" class="form-control" v-model="preview.newParams.key" placeholder="Key here..." />
                                                <input type="text" class="form-control ms-2" v-model="preview.newParams.value" placeholder="Value here..." />
                                                <button type="button" class="btn btn-primary ms-2" v-on:click="addNewParam">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" :disabled="submitDisabled" v-on:click="submit" type="button">Send <i class="fa-regular fa-paper-plane ms-1"></i></button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>
    <script type="module">
        import {
            createApp,
            toRaw,
        } from 'https://cdnjs.cloudflare.com/ajax/libs/vue/3.2.47/vue.esm-browser.prod.min.js'

        createApp({
            data() {
                return {
                    coreApis: <?php $config = require("../src/config.php");
                                echo json_encode($config['core-api']);  ?>,
                    agencyApis: <?php $config = require("../src/config.php");
                                echo json_encode($config['agency-api']);  ?>,
                    baseUrl: <?php $config = require("../src/config.php");
                                echo json_encode($config['domain'] . $config['base']);  ?>,
                    nameOptions: [],
                    successMessage: "",
                    failedMessage: "",
                    formData: {
                        action_name: "",
                        section: "",
                        urlParams: [],
                        formData: {},
                        token: ""
                    },
                    preview: {
                        url: "",
                        method: "",
                        newParams: {
                            key: "",
                            value: ""
                        },
                    },
                    paramsPreview: []
                }
            },

            methods: {
                addNewParam() {
                    if (this.preview.newParams.key && this.preview.newParams.value) {
                        this.formData.formData[this.preview.newParams.key] = this.preview.newParams.value
                    }

                    this.preview.newParams.key = "";
                    this.preview.newParams.value = "";
                },

                deleteNewParam(key) {
                    delete this.formData.formData[key];
                },

                async submit() {
                    let urlParams = [];
                    this.successMessage = "";
                    this.failedMessage = "";

                    if (typeof this.formData.urlParams === 'string') {
                        urlParams = this.formData.urlParams.split(",")
                    } else {
                        urlParams = []
                    }

                    const urlParamsData = urlParams.filter(item => item.trim() !== "").map(item => item)

                    const data = {
                        url_params: urlParamsData,
                        form_data: toRaw(this.formData.formData),
                        section: this.formData.section,
                        action_name: this.formData.action_name,
                        token: this.formData.token
                    }

                    try {
                        const response = await axios.post("/playground-post.php", data)
                        this.successMessage = JSON.stringify({
                            data: response.data.data,
                            message: response.data.message,
                            status: response.status
                        }, null, 2)
                    } catch (error) {
                        this.failedMessage = JSON.stringify({
                            ...error.response.data,
                            status: error.response.status
                        }, null, 2)
                    }

                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;

                }
            },

            watch: {
                "formData.section"(value) {
                    if (value === "agency-api") this.nameOptions = this.agencyApiOptions;

                    if (value === "core-api") this.nameOptions = this.coreApiOptions;

                    if (!value) this.nameOptions = []

                    this.formData.action_name = "";
                    this.formData.urlParams = [];
                    this.formData.formData = {};
                },

                "formData.action_name"(value) {
                    let collections = {};

                    if (this.formData.section === "agency-api") collections = toRaw(this.agencyApis);

                    if (this.formData.section === "core-api") collections = toRaw(this.coreApis);

                    const obj = collections[value];

                    this.preview.method = obj[0];
                    this.preview.url = obj[1];
                },
            },
            computed: {
                agencyApiOptions() {
                    const collections = toRaw(this.agencyApis);
                    return Object.keys(collections).map(name => name)
                },

                coreApiOptions() {
                    const collections = toRaw(this.coreApis);
                    return Object.keys(collections).map(name => name)
                },

                submitDisabled() {
                    return !this.formData.section || !this.formData.action_name
                }
            }
        }).mount('#app')
    </script>
</body>

</html>