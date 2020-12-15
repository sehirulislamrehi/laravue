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
                                    label="Name"
                                    required
                                    name="name"
                                ></v-text-field>
                                <small class="red--text" v-if='errors.name'>{{errors.name[0]}}</small>
                                <v-text-field
                                    v-model="email"
                                    label="E-mail"
                                    required
                                    name="email"
                                ></v-text-field>
                                <small class="red--text" v-if='errors.email'>{{errors.email[0]}}</small>
                                <v-text-field
                                    v-model="password"
                                    :append-icon="
                                        show1 ? 'mdi-eye' : 'mdi-eye-off'
                                    "
                                    :type="show1 ? 'text' : 'password'"
                                    name="password"
                                    label="Password"
                                    hint="At least 3 characters"
                                    counter
                                    @click:append="show1 = !show1"
                                ></v-text-field>
                                <small class="red--text" v-if='errors.password'>{{errors.password[0]}}</small>
                                <v-text-field
                                    v-model="password_confirmation"
                                    :append-icon="
                                        show2 ? 'mdi-eye' : 'mdi-eye-off'
                                    "
                                    :type="show2 ? 'text' : 'password'"
                                    name="password"
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
        errors: {},    
        show1: false,
        show2: false,
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
             this.errors = {};
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
                    let singleError = err.response.data.error
                    this.errors = {...singleError}
                    console.log(this.errors)
                    
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
