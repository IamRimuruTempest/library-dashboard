<template>
    <div>
        <v-dialog v-model="showDialog" width="800" persistent>
            <v-card>
                <!-- <v-card-title class="text-h5 grey lighten-2">
                    Add Books
                </v-card-title> -->
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2">{{ title }}</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="closeDialog()">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-form
                        @submit.prevent="InsertUpdateBook"
                        enctype="multipart/form-data"
                        ref="form"
                    >
                        <v-file-input
                            required
                            outlined
                            dense
                            persistent-placeholder
                            label="Book Cover (Recommed size: 400w - 800h)"
                            prepend-icon=""
                            prepend-inner-icon="mdi-clipboard-multiple-outline"
                            accept="image/png, image/jpeg, image/jpg"
                            ref="fileupload"
                            v-model="books.image"
                            @change="HandleImage($event)"
                            hide-details
                            class="pb-4"
                        >
                        </v-file-input>

                        <InputText
                            v-model="books.book_id"
                            label="Book Number"
                            v-mask="'############'"
                            required
                            :rules="rules"
                        />
                        <InputText
                            v-model="books.title"
                            label="Title"
                            :rules="rules"
                        />
                        <InputText
                            v-model="books.author"
                            label="Author"
                            :rules="rules"
                        />
                        <InputText
                            v-model="books.publisher"
                            label="Publisher"
                            :rules="rules"
                        />
                        <v-autocomplete
                            v-model="books.category"
                            label="Category"
                            :items="book_category"
                            return-object
                            multiple
                            clearable
                            hide-details
                            outlined
                            dense
                            class="pt-0 mb-3"
                        ></v-autocomplete>

                        <v-row class="mb-0">
                            <v-col class="pb-0 mt-0">
                                <InputText
                                    v-model="books.year"
                                    label="Year"
                                    v-mask="'####'"
                                    :rules="rules"
                                />
                            </v-col>
                            <v-col class="pb-0 mt-0">
                                <InputText
                                    v-model="books.shelf_no"
                                    label="Shelf Number"
                                    v-mask="'####'"
                                    :rules="rules"
                                />
                            </v-col>
                            <v-col class="pb-0 mt-0">
                                <InputText
                                    v-model="books.price"
                                    label="Price"
                                    :rules="rules"
                                />
                            </v-col>
                        </v-row>
                        <v-row class="mt-0">
                            <v-col class="pt-0">
                                <InputText
                                    v-model="books.isbn"
                                    label="ISBN"
                                    v-mask="'###-#-##-######-#'"
                                    :rules="isbnRules"
                                />
                            </v-col>
                            <v-col class="pt-0">
                                <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    :return-value.sync="books.date_purchased"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="books.date_purchased"
                                            label="Date Purchased"
                                            readonly
                                            outlined
                                            dense
                                            hide-details=""
                                            class="mb-3"
                                            clearable
                                            v-bind="attrs"
                                            v-on="on"
                                            :rules="rules"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="date"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="menu = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.menu.save(date)"
                                        >
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>

                        <v-autocomplete
                            v-model="books.status"
                            label="Status"
                            :items="['Available', 'Unavailable']"
                            clearable
                            hide-details
                            outlined
                            dense
                            class="pt-0 mb-3"
                            :rules="rules"
                        ></v-autocomplete>

                        <v-textarea
                            v-model="books.book_description"
                            outlined
                            auto-grow
                            rows="1"
                            row-height="15"
                            dense
                            label="Book Description"
                            hide-details
                            class="mb-3"
                            :rules="rules"
                        ></v-textarea>
                        <v-btn
                            color="primary"
                            block
                            elevation="0"
                            @click="InsertUpdateBook()"
                        >
                            {{ btn }}</v-btn
                        ></v-form
                    >
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import InputText from "../InputText.vue";
import axios from "axios";
import { mapActions, mapState } from "vuex";
export default {
    props: ["value", "items", "title", "btn"],
    components: {
        InputText,
    },
    data: () => ({
        image: [],
        books: {
            id: null,
            image: [],
            book_id: null,
            title: null,
            author: null,
            publisher: null,
            category: [],
            year: null,
            isbn: null,
            date_purchased: null,
            price: null,
            shelf_no: null,
            status: null,
            books_description: null,
        },

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],

        categoryRules: [
            (value) => {
                if (value?.length == 0) return true;

                return "";
            },
        ],

        isbnRules: [
            (value) => {
                if (value?.length == 17) return true;

                return "";
            },
        ],

        book_category: [
            "Fiction",
            "Non-fiction",
            "Mystery",
            "Thriller",
            "Romance",
            "Science fiction",
            "Fantasy",
            "Biography",
            "Autobiography",
            "History",
            "Memoir",
            "Self-help",
            "Business",
            "Travel",
            "Religion and spirituality",
            "Cookbooks",
            "Art and photography",
            "Children's books",
            "Young adult (YA) fiction",
            "Comics and graphic novels.",
        ],

        date: null,
        menu: false,
        clearObj: {},
    }),

    methods: {
        ...mapActions(["getBooks"]),

        HandleImage(event) {
            this.image = event;
            console.log(this.image);
        },

        InsertUpdateBook() {
            if (this.$refs.form.validate()) {
                let formData = new FormData();
                const config = {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                };

                if (this.btn == "Save") {
                    if (this.checkIsbn.length > 0) {
                        this.$swal({
                            icon: "warning",
                            title: "ISBN already exist!",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        return false;
                    }
                    formData.append("image", this.image);
                    formData.append("bookNum", this.books.book_id);
                    formData.append("title", this.books.title);
                    formData.append("author", this.books.author);
                    formData.append("publisher", this.books.publisher);
                    formData.append("category", this.books.category);
                    formData.append("year", this.books.year);
                    formData.append("isbn", this.books.isbn);
                    formData.append("datePurchased", this.books.date_purchased);
                    formData.append("price", this.books.price);
                    formData.append("shelfNumber", this.books.shelf_no);
                    formData.append("status", this.books.status);
                    formData.append("description", this.books.book_description);

                    axios
                        .post("/api/insert_book", formData, config)
                        .then((res) => {
                            this.$swal({
                                icon: "success",
                                title: "You have successfully added a new book",
                                showConfirmButton: false,
                                timer: 2500,
                            });
                            this.$refs.form.reset();
                            this.$emit("get-books");
                            this.showDialog = false;
                            this.image = null;
                            this.books = {};
                            // this.$refs.Insert.resetValidation();
                        })
                        .catch((error) => console.log("Error", error));
                } else {
                    formData.append("id", this.books.id);
                    formData.append("image", this.image);
                    formData.append("bookNum", this.books.book_id);
                    formData.append("title", this.books.title);
                    formData.append("author", this.books.author);
                    formData.append("publisher", this.books.publisher);
                    formData.append("category", this.books.category);
                    formData.append("year", this.books.year);
                    formData.append("isbn", this.books.isbn);
                    formData.append("datePurchased", this.books.date_purchased);
                    formData.append("price", this.books.price);
                    formData.append("shelfNumber", this.books.shelf_no);
                    formData.append("status", this.books.status);
                    formData.append("description", this.books.book_description);

                    axios
                        .post("/api/update_book", formData, config)
                        .then((res) => {
                            this.$swal({
                                icon: "success",
                                title: "Your changes have been successfully saved!!",
                                showConfirmButton: false,
                                timer: 2500,
                            });
                            this.$refs.form.reset();
                            this.$emit("get-books");
                            this.showDialog = false;
                            this.image = null;
                            this.books = {};

                            // this.$refs.Insert.resetValidation();
                        })
                        .catch((error) => console.log(error));
                }
            }
        },

        closeDialog() {
            this.$refs.form.reset();
            this.showDialog = false;
        },
    },

    computed: {
        ...mapState(["allBooks"]),
        showDialog: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit("input", value);
            },
        },

        checkIsbn() {
            return this.allBooks.filter((element) => {
                if (element.isbn === this.books.isbn) {
                    return element;
                }
            });
        },
    },

    watch: {
        showDialog() {
            if (this.btn != "Save") {
                this.books.id = this.items.id;
                this.image = this.items.book_image;
                this.books.book_id = this.items.book_id;
                this.books.title = this.items.title;
                this.books.author = this.items.author;
                this.books.publisher = this.items.publisher;
                this.books.category = this.items.category;
                // this.books.category.push(this.items.category);
                this.books.year = this.items.year;
                this.books.isbn = this.items.isbn;
                this.books.date_purchased = this.items.date_purchased;
                this.books.price = this.items.price;
                this.books.shelf_no = this.items.shelf_no;
                this.books.status = this.items.status;
                this.books.book_description = this.items.book_description;
            }
        },
    },
};
</script>

<style lang="scss" scoped></style>
