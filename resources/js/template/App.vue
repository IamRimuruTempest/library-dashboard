<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app color="#E0E0E0">
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
                        no-action
                    >
                        <template v-slot:activator>
                            <v-icon
                                slot="prependIcon"
                                large
                                color="primary"
                            ></v-icon>
                            <v-list-item-title>{{
                                item.text
                            }}</v-list-item-title>
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
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app elevation="0">
            <!-- <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon> -->
            <v-toolbar-title
                >Cagayan State University - Carig Campus
                Library</v-toolbar-title
            >
        </v-app-bar>

        <v-main class="ma-3">
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
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
                    { text: "Returned Books", to: "title" },
                ],
            },
            { text: "Events", icon: "mdi-calendar", to: "events" },
            { text: "Users", icon: "mdi-account-multiple", to: "users" },
            { text: "Account", icon: "mdi-account", to: "account" },
            { text: "Logout", icon: "mdi-location-exit", to: "logout" },
        ],
    }),
};
</script>

<style scoped>
.bg-active {
    background-color: black;
    color: white !important;
}
</style>
