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
            :items="users.data"
            :items-per-page="5"
            show-select
            @input="select_all"
            :server-items-length="users.total"
            sort-by="calories"
            class="elevation-1"
            @pagination="paginate"
            :footer-props="{
                itemsPerPageOptions: [5, 10, 15],
                itemsPerPageText: 'users Per Page',
                'show-current-page': true,
                'show-first-last-page': true
            }"
        >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>users Table</v-toolbar-title>
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
                                New User
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
                                                label="Name"
                                            ></v-text-field>
                                            <small class="red--text" v-if="errors.name">{{ errors.name[0] }}</small>
                                        </v-col>
                                         <v-col sm="6" md="12">
                                            <v-text-field
                                                v-model="editedItem.email"
                                                label="Email"
                                            ></v-text-field>
                                            <small class="red--text" v-if="errors.email">{{ errors.email[0] }}</small>
                                        </v-col>
                                        <v-col sm="6" md="12">
                                            <v-text-field
                                            v-model="editedItem.password"
                                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="show1 ? 'text' : 'password'"
                                            name="input-10-1"
                                            label="Enter Your Password"
                                            counter
                                            @click:append="show1 = !show1"
                                        ></v-text-field>
                                        <small class="red--text" v-if="errors.password">{{ errors.password[0] }}</small>
                                        </v-col>
                                        <v-col sm="6" md="12">
                                            <v-text-field
                                            v-model="editedItem.password_comfirmation"
                                            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                            :type="show2 ? 'text' : 'password'"
                                            name="input-10-1"
                                            label="Password Confirmation"
                                            counter
                                            @click:append="show2 = !show2"
                                        ></v-text-field>
                                        </v-col>
                                         <v-col sm="6" md="12">
                                            <v-select
                                                :items="roles"
                                                label="Please Select User Role"
                                                v-model="editedItem.role"
                                               
                                            >

                                            </v-select>
                                        <small class="red--text" v-if="errors.roles">{{ errors.roles[0] }}</small>

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

            <template v-slot:[`item.image`]="{ item }">
                <v-img
                    :src="item.image"
                    :lazy-src="item.image"
                    aspect-ratio="1"
                    class="grey lighten-2"
                    max-width="50"
                    max-height="50"
                >

                </v-img>
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
        show1: false,
        show2: false,
        password: 'Password',
        snackbar: false,
        selected : [],
        errors: {},
        roles: [],
        text: "",
        headers: [
            { text: "id", value : "id" },
            { text: "Name", value: "name" },
            { text: "Email", value: "email" },
            { text: "Image", value: "image" },
            { text: "Role", value: "role" },
            { text: "Actions", value: "actions", sortable: true }
        ],
        users: [],
        editedIndex: -1,
        editedItem: {
            id: "",
            name: "",
            email: "",
            role: "",
            image: "",
            password: "",
            password_comfirmation: "",
        },
        
    }),

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "New User" : "Edit User";
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
        select_all(e){
            this.selected = [];
            if( e.length > 0 ){
                this.selected = e.map( val => val.id )
            }
        },
        
        searchIt(e) {
                axios.get(`/api/users/${e}`,{
                    params: { per_page: e.itemsPerPage }
                })
                .then(  ( response ) => {
                    
                    this.users = response.data.users
                    
                })
                .catch(error => {});
        },
        
        
        paginate(e) {
            axios
                .get(`/api/users?page=${e.page}`, {
                    params: { per_page: e.itemsPerPage }
                })
                .then(res => {
                    this.users = res.data.users
                    this.roles = res.data.roles
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
            // Add a request interceptor end

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
            this.editedIndex = this.users.toString().indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        deleteItem(item) {
            this.editedIndex = this.users.toString().indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
        },

        deleteItemConfirm() {
            axios
                .post("/api/users/delete/" + this.editedItem.id)
                .then(res => {
                    this.snackbar = true;
                    this.text = "Item Deleted Successfully";
                    this.users.data.forEach((value, index) => {
                        if (res.data.user.id == value.id) {
                            this.users.data.splice(index,1);
                            this.dialogDelete = false;
                        }
                    });
                })
                .catch(err => {});
            
        },

        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.editedItem);
                this.editedIndex = -1;
            });
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.editedItem);
                this.editedIndex = -1;
            });
        },

        save() {
            this.dialog = true
            if (this.editedIndex > -1) {
                let id = this.editedItem.id;
                axios
                    .post("api/users/update/" + id, {
                        name: this.editedItem.name,
                        email: this.editedItem.email,
                        password: this.editedItem.password,
                        password_comfirmation: this.editedItem.password_comfirmation,
                        role: this.editedItem.role
                    })
                    .then(res => {
                        this.snackbar = true;
                        this.text = "Item Updated Successfully";
                        this.users.data.forEach((value, index) => {
                            if (res.data.user.id == value.id) {
                                this.users.data.splice(index,1,res.data.user);
                            }
                        });
                    })
                    .catch(err => {
                        let error = err.response.data.error;
                        this.errors = {...error}
                        this.dialog = true
                    });
            } else {
                this.errors = {};
                axios
                    .post("/api/users/add", {
                        name: this.editedItem.name,
                        email: this.editedItem.email,
                        password: this.editedItem.password,
                        password_comfirmation: this.editedItem.password_comfirmation,
                        roles: this.editedItem.roles
                    })
                    .then(res => {
                        this.dialog = true
                        this.snackbar = true,
                        this.text = "Item Added Successfully";
                        console.log(res.data.user)
                        this.users.data.push(res.data.user);
                        this.paginate({page:1, itemsPerPage: 10})
                    })
                    .catch(err => {
                        let singleError = err.response.data.error
                        this.errors = {...singleError}
                        this.dialog = true
                    });
            }
        }
    }
};
</script>

<style scoped></style>
