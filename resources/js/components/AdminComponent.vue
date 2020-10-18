<template v-slot:activator="{ on, attrs }">
  <v-app id="inspire">
    <!-- system bar start -->
    <v-system-bar app>
      <v-spacer></v-spacer>

      <v-icon>mdi-square</v-icon>

      <v-icon>mdi-circle</v-icon>

      <v-icon>mdi-triangle</v-icon>
    </v-system-bar>
    <!-- system bar end -->

    <!--- left side bar start -->
    <v-navigation-drawer v-model="drawer" app>
      <v-sheet color="grey lighten-4" class="pa-4">
        <v-avatar class="mb-4" color="grey darken-1" size="64"></v-avatar>

        <div>john@vuetifyjs.com</div>
      </v-sheet>

      <v-divider></v-divider>

      <v-list>

        <!-- single item start -->
        <v-list-item>
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>

          <v-list-item-title>Home</v-list-item-title>
        </v-list-item>
        <!-- single item end -->

        <!--- dropdown start -->
        <v-list-group no-action sub-group>
            <template v-slot:activator>
                <v-list-item-content>
                <v-list-item-title>Actions</v-list-item-title>
                </v-list-item-content>
            </template>

            <v-list-item v-for="([title, icon], i) in cruds" :key="i" link>
                <v-list-item-icon>
                <v-icon v-text="icon"></v-icon>
                </v-list-item-icon>

                <v-list-item-title v-text="title"></v-list-item-title>
            </v-list-item>
        </v-list-group>
        <!--- dropdown end -->

      </v-list>
    </v-navigation-drawer>
    <!--- left side bar end -->

    <v-main>
      <v-container class="py-8 px-6" fluid>
        <!-- header icon start -->
        <v-row>
          <v-col md="12">
            <v-btn @click="drawer = !drawer">
              <span class="material-icons"> view_headline </span>
            </v-btn>
          </v-col>
        </v-row>
        <!-- header icon end -->

        <v-row>
          <v-col cols="12">
            <v-card> </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>


<script>
export default {
  data: () => ({
    cards: ["Today"],
    drawer: true,
    cruds: [
      ["Create", "mdi-plus-outline"],
    ],
  }),
};
</script>