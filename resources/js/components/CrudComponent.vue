<template>
  <v-app>
    <!-- add start -->
    <v-dialog v-model="add_dialog" persistent max-width="600px">
      <template v-slot:activator="{ on, attrs }" style="text-align: right">
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
            <form
              @submit.prevent="submit"
              enctype="multipart/form-data"
              method="post"
            >
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
          <v-btn color="blue darken-1" text @click="add_dialog = false">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- add end -->

    <!-- table row start -->
    <v-simple-table fixed-header :loading="true">
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">Name</th>
            <th class="text-left">image</th>
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

            <!-- action start -->
            <td>
              <v-icon small class="mr-2" @click="edit(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteCrud(item.id)"> mdi-delete </v-icon>
            </td>
            <!-- action end -->

            <!-- edit modal start -->
            <v-dialog v-model="edit_dialog" persistent max-width="600px">
              <v-card>
                <v-card-title>
                  <span class="headline">Edit</span>
                </v-card-title>
                <v-card-text>
                  <validation-observer ref="observer" v-slot="{ invalid }">
                    <form
                      @submit.prevent="submit"
                      enctype="multipart/form-data"
                      method="post"
                    >
                      <!-- name start -->
                      <validation-provider
                        v-slot="{ errors }"
                        name="Name"
                        rules="required|max:10"
                      >
                        <v-text-field
                          v-model="editedItem.name"
                          :error-messages="errors"
                          label="Name"
                          required
                          value=""
                        ></v-text-field>
                      </validation-provider>
                      <!-- name end -->

                      <!-- image start -->
                      <validation-provider
                        v-slot="{ errors }"
                        name="image"
                        rules="required"
                      >
                        <v-img
                          max-height="100"
                          max-width="100"
                          :src="'/images/crud/' + editedItem.image"
                        ></v-img>
                        <v-file-input
                          show-size
                          label="File input"
                          :error-messages="errors"
                          v-model="editedItem.image"
                          type="file"
                          v-on:change="onFileChange"
                        ></v-file-input>
                      </validation-provider>

                      <!-- image end -->

                      <v-btn
                        class="mr-4"
                        type="submit"
                        :disabled="invalid"
                        @click="update"
                      >
                        Update
                      </v-btn>
                    </form>
                  </validation-observer>
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
                </v-card-actions>
              </v-card>
            </v-dialog>
            <!-- edit modal end -->
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    <!-- table row end -->

    <!-- snackbar start -->
    <v-snackbar
      v-model="snackbar"
      :timeout="timeout"
      v-for="error in errors"
      :key="error.message"
    >
      {{ error.message }}

      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <!-- snackbar end -->
    <!-- snackbar start -->
    <v-snackbar
      v-model="success"
    >

      {{text}}

      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <!-- snackbar end -->
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
  setInteractionMode,
} from "vee-validate";
setInteractionMode("eager");

extend("required", {
  ...required,
  message: "{_field_} can not be empty",
});

extend("max", {
  ...max,
  message: "{_field_} may not be greater than {length} characters",
});

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      add_dialog: false,
      timeout: 0,
      edit_dialog: false,
      dialog: false,
      snackbar: false,
      success: false,
      text: "",
      cruds: [],
      name: "",
      image: "",
      public_image: null,
      errors: [],
      editedItem: {
        name: "",
        image: "",
      },
    };
  },

  created() {
    this.initialize();
  },

  watch: {
    cruds: (v) => {},
  },

  methods: {
    initialize() {
      axios
        .get("/api/cruds/all", {})
        .then((res) => {
          this.cruds = res.data.crud;
          for (let x in res.data.crud) {
            this.public_image = res.data.crud[x].image;
          }
        })
        .catch((err) => {});
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
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          this.cruds.push(res.data.crud);
          this.text = "Information Added Successfully";
          this.success = true;
        })
        .catch((err) => {
          for (let x in err.response.data.errors.name) {
            this.errors.push({
              message: err.response.data.errors.name[x],
            });
          }
          this.snackbar = true;
        });
    },
    edit(item) {
      this.editedItem = Object.assign({}, item);
      this.edit_dialog = true;
    },
    update() {
      let id = this.editedItem.id;
      let formData = new FormData();
      formData.append("name", this.editedItem.name);
      formData.append("image", this.editedItem.image);

      axios
        .post("/api/cruds/update/" + id, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          this.success = true;
          this.text = "Item Updated Successfully"
          Object.assign(this.cruds[id - 1], res.data.crud)
        })
        .catch((err) => {
          for (let x in err.response.data.errors.name) {
            this.errors.push({
              message: err.response.data.errors.name[x],
            });
          }
          this.snackbar = true;
        });
    },
  },
};
</script>
