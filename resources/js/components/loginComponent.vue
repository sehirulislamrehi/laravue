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
                            <v-form ref="form">
                                <v-text-field
                                    v-model="email"
                                    label="E-mail"
                                    required
                                ></v-text-field>
                                <v-small class="form-error" v-if="errors.email" >{{ errors.email[0] }}</v-small>

                                <v-text-field
                                    color="error"
                                    v-model="password"
                                    :append-icon="
                                        show1 ? 'mdi-eye' : 'mdi-eye-off'
                                    "
                                    :type="show1 ? 'text' : 'password'"
                                    name="input-10-1"
                                    label="Password"
                                    hint="At least 3 characters"
                                    counter
                                    required
                                    @click:append="show1 = !show1"
                                ></v-text-field>
                                <v-small class="form-error" v-if="errors.password" >{{ errors.password[0] }}</v-small>

                                <v-btn
                                    color="success"
                                    class="mr-4"
                                    @click="login"
                                >
                                    Login
                                </v-btn>
                            </v-form>
                            <!-- form end -->
                            <div style="margin-top: 15px;">
                                <p>
                                    Not yet registered?
                                    <router-link to="/register"
                                        >Go to register page</router-link
                                    >
                                </p>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
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
            show1: false,
            show2: true,
            show3: false,
            show4: false,
            loading: false,
            snackbar: false,
            errors: {},
            text: "",
            password: "",
            valid: false,
            email: ""
        };
    },
    methods: {
        login: function() {
            // Add a request interceptor
            axios.interceptors.request.use(
                config => {
                    this.loading = true;
                    return config;
                },
                error => {
                    this.loading = false;
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
                .post("/api/login", {
                    email: this.email,
                    password: this.password
                })
                .then(res => {
                    localStorage.setItem("token", res.data.token);
                    localStorage.setItem("LoggedIn", true);
                    if(res.data){
                        console.log(res.data.isAdmin)
                        this.$router
                        .push("/admin")
                        .then(res => {
                            console.log("Logged in successfully");
                        })
                        .catch(err => console.log(err));
                    }   
                    else{
                        this.text = "You need to loggedIn as an Admin"
                        this.snackbar = true
                    }
                    
                })
                .catch(err => {
                    this.loading = false;
                    if( err.response.data.message ){
                        this.snackbar = true;
                        this.text = err.response.data.message;
                    }
                    let singleError = err.response.data.error
                    this.errors = {...singleError}
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
