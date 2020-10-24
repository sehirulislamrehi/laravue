<template>
    <v-app>
        <v-card class="login_component">
            <v-container class="container">
                <v-row>
                    <v-col md="4" offset-md="4">
                        <v-card class="mx-auto login-card" outlined>
                            <!-- form start -->
                            <v-form ref="form">

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
                                    hint="At least 8 characters"
                                    counter
                                    @click:append="show1 = !show1"
                                ></v-text-field>

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
                                    <router-link to="/register">Go to register page</router-link>
                                </p>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
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
            password: "",
            rules: {
                required: value => !!value || "Required.",
                min: v => v.length >= 8 || "Min 8 characters",
                emailMatch: () =>
                    "The email and password you entered don't match"
            },
            valid: false,
            email: "",
            emailRules: [
                v => !!v || "E-mail is required",
                v => /.+@.+/.test(v) || "E-mail must be valid"
            ]
        };
    },
    methods: {
        login: function() {
            localStorage.setItem("token", "asdkokcsmiowqelsapxasbh");
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
