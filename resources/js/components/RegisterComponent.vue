<template>
    <v-app>
        <v-card class="login_component">
            <v-container class="container">
                <v-row>
                    <v-col md="4" offset-md="4">
                        <v-card class="mx-auto login-card" outlined>
                            <v-progress-linear
                                :active="loading"
                                :indeterminate="loading"
                                absolute
                                top
                                color="deep-purple accent-4"
                            ></v-progress-linear>
                            <!-- form start -->
                            <v-form ref="form" v-model="valid" lazy-validation>
                                <v-text-field
                                    v-model="name"
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

                                <v-text-field
                                    v-model="password"
                                    :append-icon="
                                        show1 ? 'mdi-eye' : 'mdi-eye-off'
                                    "
                                    :rules="[rules.required, rules.min]"
                                    :type="show1 ? 'text' : 'password'"
                                    name="input-10-1"
                                    label="Password"
                                    hint="At least 3 characters"
                                    counter
                                    @click:append="show1 = !show1"
                                ></v-text-field>

                                <v-text-field
                                    v-model="password_confirmation"
                                    :append-icon="
                                        show2 ? 'mdi-eye' : 'mdi-eye-off'
                                    "
                                    :rules="[rules.required, rules.min]"
                                    :type="show2 ? 'text' : 'password'"
                                    name="input-10-1"
                                    label="Confirm Password"
                                    hint="At least 3 characters"
                                    counter
                                    @click:append="show2 = !show2"
                                ></v-text-field>

                                <v-btn
                                    color="success"
                                    class="mr-4"
                                    @click="register"
                                >
                                    Register
                                </v-btn>
                            </v-form>
                            <!-- form end -->
                            <div style="margin-top: 15px;">
                                <p>
                                    Already registered?
                                    <router-link to="/login"
                                        >Go to login page</router-link
                                    >
                                </p>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
        <!-- snackbar start -->
        <v-snackbar v-model="snackbar" >
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
        valid: true,
        nameRules: [v => !!v || "Name is required"],
        emailRules: [
            v => !!v || "E-mail is required",
            v => /.+@.+\..+/.test(v) || "E-mail must be valid"
        ],
        show1: false,
        show2: false,
        rules: {
            required: value => !!value || "Required.",
            min: v => v.length >= 3 || "Min 3 characters",
            emailMatch: () => "The email and password you entered don't match"
        },
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        loading: false,
        snackbar: false,
        text: "",
    }),
    methods: {
        register: function() {
            // Add a request interceptor
            axios.interceptors.request.use(
                config => {
                    this.loading = true;
                    return config;
                },
                error => {
                    // Do something with request error
                    return Promise.reject(error);
                }
            );

            // Add a response interceptor
            axios.interceptors.response.use(
                response => {
                    this.loading = false;
                    return response;
                },
                error => {
                    // Any status codes that falls outside the range of 2xx cause this function to trigger
                    // Do something with response error
                    return Promise.reject(error);
                }
            );
            axios
                .post("/api/register", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
                .then(res => {
                    this.snackbar = true;
                    this.text = res.data[1];
                })
                .catch(err => {
                    this.loading = false
                    this.snackbar = true
                    let singleError = err.response.data.message
                    this.text = singleError
                });
        }
    }
};
</script>

<style scoped>
.login-card {
    padding: 30px;
}
.login_component {
    height: 100vh;
    position: relative;
    background: #9fa8da;
}
.login_component .container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
