<template>
    <div>
        <v-dialog v-model="showDialog" width="800" persistent>
            <v-card>
                <!-- <v-card-title class="text-h5 grey lighten-2">
                    Add Books
                </v-card-title> -->
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2"
                        >Book Information</v-toolbar-title
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
                            <img
                                :src="`/book-cover/${book.book_image}`"
                                width="100%"
                            />
                        </v-col>
                        <v-col cols="8">
                            <span>Book ID: {{ book.book_id }}</span>
                            <h1>{{ book.title }}</h1>
                            <p
                                class="font-weight-bold"
                                style="font-size: 1.2rem"
                            >
                                {{ book.author }}
                            </p>
                            <p
                                class="font-weight-bold"
                                style="font-size: 1.2rem"
                            >
                                {{ book.isbn }}
                            </p>
                            <p style="font-size: 1rem">
                                Publisher: {{ book.publisher }}
                            </p>
                            <p style="font-size: 1rem">
                                Category: {{ book.category }}
                            </p>
                            <p style="font-size: 1rem">
                                Year Published: {{ book.year }}
                            </p>
                            <p style="font-size: 1rem">
                                Date Purchased: {{ book.date_purchased }}
                            </p>
                            <p style="font-size: 1rem">
                                Book Price: {{ book.price }}
                            </p>
                            <p style="font-size: 1rem">
                                Shelf Number: {{ book.shelf_no }}
                            </p>
                            <p style="font-size: 1rem">
                                Description: {{ book.book_description }}
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
        book: {},
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

        imageUrl() {
            return `/public/book-cover/031580532.png`;
        },
    },

    watch: {
        showDialog() {
            this.book = this.items;
        },
    },
};
</script>

<style lang="scss" scoped></style>
