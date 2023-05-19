<template>
    <div>
        <v-dialog v-model="showDialog" width="800" persistent>
            <v-card>
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2"
                        >Event Information</v-toolbar-title
                    >

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="showDialog = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-row>
                        <v-col cols="4">
                            <img :src="`/events/${event.image}`" width="100%" />
                        </v-col>
                        <v-col cols="8">
                            <h1>{{ event.title }}</h1>
                            <br />
                            <p
                                class="font-weight-bold"
                                style="font-size: 1.2rem"
                            >
                                {{ event.subtitle }}
                            </p>

                            <p style="font-size: 1rem">
                                Location: {{ event.location }}
                            </p>
                            <p style="font-size: 1rem">
                                Date: {{ event.date }}
                                {{ event.time }}
                            </p>

                            <p style="font-size: 1rem">
                                Status: {{ event.status }}
                            </p>
                            <p style="font-size: 1rem">
                                Description: {{ event.description }}
                            </p>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from "axios";
export default {
    props: ["value", "items"],
    data: () => ({
        event: {},
    }),
    computed: {
        showDialog: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit("input", value);
            },
        },
    },

    watch: {
        showDialog() {
            this.event = this.items;
        },
    },
};
</script>

<style lang="scss" scoped></style>
