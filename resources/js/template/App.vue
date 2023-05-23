<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            v-if="loggedInUser != null"
            app
            color="#E0E0E0"
        >
            <v-list dense>
                <div class="text-center">
                    <img
                        src="../../../public/images/logo.png"
                        width="150px"
                        height="100%"
                        alt=""
                    />

                    <!-- <v-subheader class="text-center align-center"
                        >List of Components</v-subheader
                    > -->
                </div>

                <div v-for="(item, i) in items" :key="i">
                    <v-list-item
                        v-if="item.text != 'Books'"
                        :to="item.to"
                        dense
                        color="primary"
                    >
                        <v-list-item-icon>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>{{
                            item.text
                        }}</v-list-item-content>
                    </v-list-item>

                    <v-list-group
                        v-if="item.text == 'Books'"
                        :value="true"
                        :prepend-icon="item.icon"
                        v-model="active"
                    >
                        <template v-slot:activator>
                            <v-icon
                                slot="prependIcon"
                                large
                                color="primary"
                            ></v-icon>
                            <v-list-item-title
                                style="font-size: 1rem; font-weight: 400"
                                >{{ item.text }} Hello</v-list-item-title
                            >
                        </template>
                        <div class="ml-14">
                            <v-list-item
                                v-for="(child, index) in item.child"
                                :key="index"
                                :to="child.to"
                            >
                                <v-list-item-content>
                                    <v-list-item-title link>{{
                                        child.text
                                    }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </div>
                    </v-list-group>
                </div>
                <v-list-item button @click="logout()">
                    <v-list-item-icon>
                        <v-icon>mdi-location-exit</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>Logout</v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app elevation="0">
            <!-- <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon> -->
            <v-toolbar-title
                >Cagayan State University - Carig Campus
                Library</v-toolbar-title
            >
            <v-spacer></v-spacer>
            <!-- <span class="text-right ma-0">
                {{ loggedInUser.name }}<br />
                <span style="font-size: 0.8rem">{{ loggedInUser.email }}</span>
            </span> -->
        </v-app-bar>

        <v-main class="ma-3">
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
    data: () => ({
        drawer: null,
        items: [
            { text: "Dashboard", icon: "mdi-view-dashboard", to: "dashboard" },
            {
                text: "Books",
                icon: "mdi-book-multiple",
                child: [
                    { text: "All Books", to: "books" },
                    { text: "Borrowed Books", to: "books-borrowed" },
                    { text: "Returned Books", to: "books-returned" },
                ],
            },
            { text: "Events", icon: "mdi-calendar", to: "school-events" },
            { text: "Users", icon: "mdi-account-multiple", to: "users" },
            { text: "Account", icon: "mdi-account", to: "account" },
        ],
        active: false,
    }),

    methods: {
        ...mapActions(["logout", "storeUser"]),
        logout() {
            this.$swal({
                title: "Hope to see you back soon",
                text: "Are you sure you want to logout from CSU-Library and Information Services",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, I'm sure",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.storeUser(null);
                    this.$router.push("/login");
                }
            });
        },
    },

    computed: {
        ...mapState(["loggedInUser"]),
    },

    created() {},
};
</script>

<style scoped>
.bg-active {
    background-color: black;
    color: white !important;
}
</style>
