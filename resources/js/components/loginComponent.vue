<template>
  <v-app  >

      <v-container class="container">
        <v-row>
          <v-col md="4" offset-md="4">
            <v-card class="mx-auto login-card" outlined>
              <!-- form start -->
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-text-field
                  v-model="name"
                  :counter="20"
                  :rules="nameRules"
                  label="Name"
                  required
                ></v-text-field>

                <v-text-field
                  v-model="email"
                  :rules="emailRules"
                  label="E-mail"
                  required
                ></v-text-field>


                <v-btn link to="/admin"
                  color="success"
                  class="mr-4"
                >
                  Login
                </v-btn>
                
              </v-form>
              <!-- form end -->
            </v-card>
          </v-col>
        </v-row>
      </v-container>
      
  </v-app>
</template>

<script>
export default {
  data: () => ({
    valid: true,
    name: "",
    nameRules: [
      (v) => !!v || "Name is required",
      (v) => (v && v.length <= 20) || "Name must be less than 20 characters",
    ],
    email: "",
    emailRules: [
      (v) => !!v || "E-mail is required",
      (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
    ],
    select: null,
    items: ["Item 1", "Item 2", "Item 3", "Item 4"],
    checkbox: false,
  }),

  methods: {
    validate() {
      this.$refs.form.validate();
    },
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
  },
};
</script>

<style scoped>
.login-card {
  padding: 30px;
}
.login_component {
  height: 100vh;
  position: relative;
  background: #9FA8DA;
}
.login_component .container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>