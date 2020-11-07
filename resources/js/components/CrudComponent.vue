<template>
    <v-app>
        <!-- add start -->
        <v-dialog v-model="add_dialog" persistent max-width="600px">
            <template
                v-slot:activator="{ on, attrs }"
                style="text-align: right"
            >
                <v-btn color="primary small-btn" dark v-bind="attrs" v-on="on">
                    Add
                </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="headline">Add Item</span>
                </v-card-title>
                <v-card-text>
                    <validation-observer ref="observer" v-slot="{ invalid }">
                        <form @submit.prevent="submit" enctype="multipart/form-data" method="post">
                            <!-- name start -->
                            <validation-provider
                                v-slot="{ errors }"
                                name="Name"
                                rules="required|max:10"
                            >
                                <v-text-field
                                    v-model="name"
                                    :counter="10"
                                    :error-messages="errors"
                                    label="Name"
                                    required
                                ></v-text-field>
                            </validation-provider>
                            <!-- name end -->

                            <!-- image start -->
                            <validation-provider
                                v-slot="{ errors }"
                                name="image"
                                rules="required"
                            >
                                <v-file-input
                                show-size
                                label="File input"
                                :error-messages="errors"
                                v-model="image"
                                required
                                type="file"
                                v-on:change="onFileChange"
                            ></v-file-input>
                            </validation-provider>
                            
                            <!-- image end -->

                            <v-btn
                                class="mr-4"
                                type="submit"
                                :disabled="invalid"
                                @click="add"
                            >
                                submit
                            </v-btn>
                        </form>
                    </validation-observer>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="add_dialog = false"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- add end -->

        <!-- table row start -->
        <v-simple-table fixed-header height="300px">
            <template v-slot:default>
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th class="text-left">Image</th>
                        <th class="text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in desserts" :key="item.name">
                        <td>{{ item.name }}</td>
                        <td>{{ item.calories }}</td>
                        <td>
                            <v-icon small class="mr-2" @click="dialog = true">
                                mdi-pencil
                            </v-icon>
                            <v-icon small> mdi-delete </v-icon>
                        </td>
                        <v-dialog v-model="dialog" persistent max-width="600px">
                            <v-card>
                                <v-card-title>
                                    <span class="headline">User Profile</span>
                                </v-card-title>
                                <v-card-text> </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="dialog = false"
                                    >
                                        Close
                                    </v-btn>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="dialog = false"
                                    >
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>
        <!-- table row end -->
    </v-app>
</template>

<style scoped>
.small-btn {
    width: 150px;
}
</style>

<script>
import { required, email, max } from "vee-validate/dist/rules";
import {
    extend,
    ValidationObserver,
    ValidationProvider,
    setInteractionMode
} from "vee-validate";
setInteractionMode("eager");

extend("required", {
    ...required,
    message: "{_field_} can not be empty"
});

extend("max", {
    ...max,
    message: "{_field_} may not be greater than {length} characters"
});



export default {
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            add_dialog: false,
            dialog: false,
            desserts: [],
            name: "",
            image: ""
        };
    },
    onFileChange(e){
        console.log(e.target.files[0]);
        this.file = e.target.files[0];
    },
    methods: {
        submit() {
            this.$refs.observer.validate();
        },
        add(){
            axios.post('/api/cruds/add',{
                name : this.name,
                image : this.image.name,
            })
            .then( res => {
                console.log(res)
            })
            .catch( err => {

            })
        }
    }
};
</script>
