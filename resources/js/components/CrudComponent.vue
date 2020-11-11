<template>
    <v-app>
        <!-- add start -->
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
            class="add_button"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    style="width: 150px"
                >
                    Add
                </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="headline">User Profile</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <form action="">
                            <v-row>
                                <v-col md="12">
                                    <v-text-field
                                        label="Legal first name*"
                                        required
                                        name="name"
                                        v-model="name"
                                    ></v-text-field>
                                </v-col>
                                <v-col md="12">
                                    <v-file-input
                                        accept="image/*"
                                        label="File input"
                                        name="image"
                                        v-model="image"
                                    ></v-file-input>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="add">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- add end -->

        <v-simple-table>
            <template v-slot:default>
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th class="text-left">Image</th>
                        <th class="text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in cruds" :key="item.id">
                        <td>{{ item.name }}</td>
                        <td>
                            <v-img
                                max-height="100"
                                max-width="100"
                                :src="'/images/crud/' + item.image"
                            ></v-img>
                        </td>
                        <td>
                            <v-icon small class="mr-2" @click="edit(item)">
                                mdi-pencil
                            </v-icon>
                            <!-- edit start -->
                            <v-dialog
                                v-model="edit_dialog"
                                persistent
                                max-width="600px"
                                class="add_button"
                            >
                                <v-card>
                                    <v-card-title>
                                        <span class="headline"
                                            >Edit Profile</span
                                        >
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container>
                                            <form action="">
                                                <v-row>
                                                    <v-col md="12">
                                                        <v-text-field
                                                            label="Legal first name*"
                                                            required
                                                            name="name"
                                                            v-model="
                                                                editedItem.name
                                                            "
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col md="12">
                                                        <v-img
                                                            max-height="100"
                                                            max-width="100"
                                                            :src="
                                                                '/images/crud/' +
                                                                    editedItem.image
                                                            "
                                                        ></v-img>
                                                        <v-file-input
                                                            accept="image/*"
                                                            label="File input"
                                                            name="image"
                                                            v-model="
                                                                editedItem.image
                                                            "
                                                        ></v-file-input>
                                                    </v-col>
                                                </v-row>
                                            </form>
                                        </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="edit_dialog = false"
                                        >
                                            Close
                                        </v-btn>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="update"
                                        >
                                            Save
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <!-- edit end -->
                            <v-icon small @click="deleteItem(item)">
                                mdi-delete
                            </v-icon>
                            <!-- delete modal start -->
                            <v-dialog v-model="delete_dialog" width="500">

                                <v-card>
                                    <v-card-title
                                        class="headline grey lighten-2"
                                    >
                                        Are you sure want to delete this? 
                                    </v-card-title>

                                    <v-divider></v-divider>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="primary"
                                            text
                                            @click="delete_dialog = false"
                                        >
                                            No
                                        </v-btn>
                                        <v-btn
                                            color="primary"
                                            text
                                            @click="delete_item"
                                        >
                                            Yes
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <!-- delete modal end -->
                        </td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>
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
    data() {
        return {
            cruds: [],
            public_image: null,
            dialog: false,
            edit_dialog: false,
            name: "",
            image: "",
            snackbar: false,
            delete_dialog: false,
            text: "",
            editedItem: {
                name: "",
                image: ""
            }
        };
    },
    watch: {
        cruds: v => {}
    },
    created() {
        this.initialize();
    },
    methods: {
        initialize() {
            axios
                .get("/api/cruds/all", {})
                .then(res => {
                    this.cruds = res.data.crud;
                    for (let x in res.data.crud) {
                        this.public_image = res.data.crud[x].image;
                    }
                })
                .catch(err => {});
        },
        submit() {
            this.$refs.observer.validate();
        },
        onFileChange(e) {
            this.image = e;
        },
        add() {
            let form = new FormData();
            form.append("image", this.image);
            form.append("name", this.name);
            axios
                .post("/api/cruds/add", form, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(res => {
                    this.cruds.push(res.data.crud);
                    this.text = "Information Added Successfully";
                    this.snackbar = true;
                    this.dialog = false;
                })
                .catch(err => {
                    this.snackbar = true;
                    for (let x in err.response.data.errors.name) {
                        this.text = err.response.data.errors.name[x];
                    }
                });
        },
        edit(item) {
            this.editedItem = Object.assign({}, item);
            this.edit_dialog = true;
        },
        deleteItem(item){
            this.editedItem = Object.assign({},item)
            this.delete_dialog = true;
        },
        update() {
            let id = this.editedItem.id;
            let formData = new FormData();
            formData.append("name", this.editedItem.name);
            formData.append("image", this.editedItem.image);

            axios
                .post("/api/cruds/update/" + id, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(res => {
                    this.snackbar = true;
                    this.text = "Item Updated Successfully";
                    this.edit_dialog = false;
                    this.cruds.filter((value, index) => {
                        if (res.data.crud.id == value.id) {
                            return this.cruds.splice(index, 1, res.data.crud);
                        }
                    });
                })
                .catch(err => {
                    for (let x in err.response.data.errors.name) {
                        this.text = err.response.data.errors.name[x];
                    }
                    this.snackbar = true;
                });
        },
        delete_item(){
            let id = this.editedItem.id
            axios.post("/api/cruds/delete/"+id)
            .then( res => {
                this.snackbar = true
                this.text = "Item Deleted Successfully"
                this.delete_dialog = false
                this.cruds.filter((value, index) => {
                    if(res.data.crud.id == value.id ){
                        return this.cruds.splice(index,1)
                    }
                })
            })  
            .catch( res => {
                for (let x in err.response.data.errors.name) {
                    this.text = err.response.data.errors.name[x];
                }
                this.snackbar = true;
            })
        }
    }
};
</script>

<style scoped>
.add_button {
    display: inline;
}
</style>
