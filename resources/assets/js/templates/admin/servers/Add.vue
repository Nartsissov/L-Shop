<template>
    <v-layout align-center justify-center>
        <v-flex xs12 sm12 md8 lg6>
            <v-card>
                <v-card-title class="text-xs-center">
                    <h3>{{ $t('content.admin.servers.add.title') }}</h3>
                    <v-spacer></v-spacer>
                    <span class="caption" v-html="$t('common.unclear_docs', {link: 'https://github.com/D3lph1/L-shop/wiki'})"></span>
                </v-card-title>
                <v-card-text>
                    <section>
                        <v-text-field
                                v-model="name"
                                :label="$t('content.admin.servers.add.name')"
                                prepend-icon="label"
                        ></v-text-field>
                    </section>
                    <section>
                        <h2 class="subheading">{{ $t('content.admin.servers.add.categories') }}</h2>
                        <div class="text-xs-right">
                            <v-text-field
                                    v-for="each in n"
                                    :key="each - 1"
                                    v-model="categories[each - 1]"
                                    :label="$t('content.admin.servers.add.category_name')"
                                    prepend-icon="category"
                                    append-icon="close"
                                    :append-icon-cb="() => {categories.splice(each - 1, 1); n--}"
                            ></v-text-field>
                            <v-btn
                                    color="primary"
                                    @click="() => {n++; categories.push('')}"
                            >{{ $t('common.add') }}</v-btn>
                        </div>
                    </section>
                    <section>
                        <h2 class="subheading">{{ $t('content.admin.servers.add.connecting') }}</h2>
                        <v-form class="text-xs-right">
                            <v-text-field
                                    v-model="ip"
                                    :label="$t('content.admin.servers.add.ip')"
                                    prepend-icon="settings_ethernet"
                            ></v-text-field>
                            <v-text-field
                                    v-model="port"
                                    :label="$t('content.admin.servers.add.port')"
                                    prepend-icon="import_export"
                            ></v-text-field>
                            <v-text-field
                                    type="password"
                                    v-model="password"
                                    :label="$t('content.admin.servers.add.password')"
                                    prepend-icon="lock"
                            ></v-text-field>
                            <v-checkbox
                                    color="secondary"
                                    :label="$t('content.admin.servers.add.monitoring_enabled')"
                                    v-model="monitoringEnabled"
                            ></v-checkbox>
                            <v-checkbox
                                    color="secondary"
                                    :label="$t('content.admin.servers.add.server_enabled')"
                                    v-model="serverEnabled"
                            ></v-checkbox>
                        </v-form>
                    </section>
                    <section class="mt-2">
                        <h2 class="subheading">{{ $t('content.admin.servers.add.distribution') }}</h2>
                        <v-form class="text-xs-right">
                            <v-select
                                    :items="distributors"
                                    v-model="distributor"
                                    :label="$t('content.admin.servers.add.distributor')"
                            ></v-select>
                        </v-form>
                    </section>
                </v-card-text>
                <v-card-actions>
                    <v-btn flat color="orange" :disabled="finishDisabled" :loading="finishLoading" @click="perform">{{ $t('content.admin.servers.add.finish') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import loader from './../../../core/http/loader'

    export default {
        data() {
            return {
                name: '',
                n: 1,
                categories: [''],
                ip: '',
                port: '',
                password: '',
                monitoringEnabled: false,
                serverEnabled: false,
                distributors: [],
                distributor: null,
                finishLoading: false,
            }
        },
        beforeRouteEnter (to, from, next) {
            loader.beforeRouteEnter('/spa/admin/servers/add', to, from, next);
        },
        beforeRouteUpdate (to, from, next) {
            loader.beforeRouteUpdate('/spa/admin/servers/add', to, from, next, this);
        },
        computed: {
            finishDisabled() {
                return this.name === '' || this.distributor === null;
            }
        },
        methods: {
            perform() {
                this.finishLoading = true;

                this.$axios.post('/spa/admin/servers/add', {
                    name: this.name,
                    categories: this.categories,
                    ip: this.ip,
                    port: this.port,
                    password: this.password,
                    monitoring_enabled: this.monitoringEnabled,
                    server_enabled: this.serverEnabled,
                    distributor: this.distributor
                })
                    .then(response => {
                        this.finishLoading = false;
                        if (response.data.status === 'success') {
                            this.$router.push({name: 'admin.servers.list'});
                        }
                    });
            },
            setData(response) {
                const data = response.data;

                this.distributors = data.distributors;
            }
        }
    }
</script>
