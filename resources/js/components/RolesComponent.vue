<template>
    <v-app>
        <v-card-title>
            <!-- search field start -->
            <v-text-field
                append-icon="mdi-magnify"
                label="Search"
                @input="searchIt"
            ></v-text-field>
            <!-- search field end -->
        </v-card-title>

        <v-data-table
            :headers="headers"
            :items="roles.data"
            :items-per-page="5"
            :server-items-length="roles.total"
            sort-by="calories"
            class="elevation-1"
            @pagination="paginate"
            :footer-props="{
                itemsPerPageOptions: [5, 10, 15],
                itemsPerPageText: 'Roles Per Page',
                'show-current-page': true,
                'show-first-last-page': true
            }"
        >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Roles Table</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                v-bind="attrs"
                                v-on="on"
                            >
                                New Role
                            </v-btn>
                        </template>

                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col sm="6" md="12">
                                            <v-text-field
                                                v-model="editedItem.name"
                                                label="Role name"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="close"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn color="blue darken-1" text @click="save">
                                    Save
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="headline"
                                >Are you sure you want to delete this
                                item?</v-card-title
                            >
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="closeDelete"
                                    >Cancel</v-btn
                                >
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="deleteItemConfirm()"
                                    >OK</v-btn
                                >
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
            </template>
        </v-data-table>

        <!-- snackbar start -->
        <v-snackbar v-model="snackbar">
            {{ text }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="pink"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <!-- snackbar end -->
    </v-app>
</template>

<script>
export default {
    data: () => ({
        dialog: false,
        dialogDelete: false,
        snackbar: false,
        text: "",
        headers: [
            {
                text: "#Id",
                align: "start",
                sortable: true,
                value: ""
            },
            { text: "Name", value: "name" },
            { text: "Created At", value: "created_at" },
            { text: "Updated At", value: "updated_at" },
            { text: "Actions", value: "actions", sortable: true }
        ],
        roles: [],
        editedIndex: -1,
        editedItem: {
            name: ""
        },
        defaultItem: {
            name: ""
        }
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "New Role" : "Edit Role";
        }
    },

    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        }
    },

    created() {
        this.initialize();
    },

    methods: {
        searchIt(e) {
            axios
                .get(`/api/roles/search/${e}`)
                .then(response => {
                    this.roles = response.data.roles;
                })
                .catch(error => {
                    console.dir(error.response);
                });
        },
        paginate(e) {
            axios
                .get(`/api/roles?page=${e.page}`, {
                    params: { per_page: e.itemsPerPage }
                })
                .then(res => {
                    this.roles = res.data.roles;
                });
        },
        initialize() {
            // Add a request interceptor
            axios.interceptors.request.use(
                function(config) {
                    // Do something before request is sent
                    return config;
                },
                function(error) {
                    // Do something with request error
                    return Promise.reject(error);
                }
            );

            // Add a response interceptor
            axios.interceptors.response.use(
                function(response) {
                    // Any status code that lie within the range of 2xx cause this function to trigger
                    // Do something with response data
                    return response;
                },
                function(error) {
                    // Any status codes that falls outside the range of 2xx cause this function to trigger
                    // Do something with response error
                    return Promise.reject(error);
                }
            );
        },

        editItem(item) {
            this.editedIndex = this.roles.toString().indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        deleteItem(item) {
            this.editedIndex = this.roles.toString().indexOf(item);
            this.editedItem = Object.assign({}, item);

            this.dialogDelete = true;
        },

        deleteItemConfirm() {
            axios
                .post("/api/roles/delete/" + this.editedItem.id)
                .then(res => {
                    this.snackbar = true;
                    this.text = "Item Deleted Successfully";
                })
                .catch(err => {});
            this.roles.splice(this.editedIndex, 1);
            this.closeDelete();
        },

        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        save() {
            if (this.editedIndex > -1) {
                let id = this.editedItem.id;
                axios
                    .post("api/roles/update/" + id, {
                        name: this.editedItem.name
                    })
                    .then(res => {
                        this.snackbar = true;
                        this.text = "Item Updated Successfully";
                        this.roles.data.forEach((value, index) => {
                            if (res.data.role.id == value.id) {
                                return this.roles.data.splice(index,1,res.data.role);
                            }
                        });
                    })
                    .catch(err => {
                        let error = err.response.data.message;
                    });
            } else {
                axios
                    .post("/api/roles/add", {
                        name: this.editedItem.name
                    })
                    .then(res => {
                        (this.snackbar = true),
                            (this.text = "Item Added Successfully");
                        this.roles.push(res.data.role);
                    })
                    .catch(err => {
                        let errors = err.response.data.errors.name;
                        for (let x in errors) {
                            this.snackbar = true;
                            this.text = errors[x];
                        }
                    });
            }
            this.close();
        }
    }
};
</script>

<style scoped></style>
