<template>
    <div>
        <v-dialog v-model="showDialog" width="600" persistent>
            <v-card>
                <!-- <v-card-title class="text-h5 grey lighten-2">
                    Add Books
                </v-card-title> -->
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2">{{ title }}</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="showDialog = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-form
                        @submit.prevent="InsertUpdateEvent"
                        enctype="multipart/form-data"
                    >
                        <v-file-input
                            required
                            outlined
                            dense
                            persistent-placeholder
                            label="Event Cover (Recommed size: 400w - 800h)"
                            prepend-icon=""
                            prepend-inner-icon="mdi-clipboard-multiple-outline"
                            accept="image/png, image/jpeg, image/jpg"
                            ref="fileupload"
                            v-model="events.image"
                            @change="HandleImage($event)"
                            hide-details
                            class="pb-4"
                        >
                        </v-file-input>

                        <InputText v-model="events.title" label="Event Title" />
                        <InputText v-model="events.subtitle" label="Subtitle" />
                        <InputText
                            v-model="events.location"
                            label="Location"
                            class="pb-3"
                        />
                        <v-row>
                            <v-col class="pt-0">
                                <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    :return-value.sync="events.date"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="events.date"
                                            label="Event Date"
                                            readonly
                                            outlined
                                            dense
                                            hide-details=""
                                            class="mb-3"
                                            clearable
                                            v-bind="attrs"
                                            v-on="on"
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

                        <InputText v-model="events.time" label="Time" />

                        <v-autocomplete
                            v-model="events.status"
                            label="Status"
                            :items="['Upcoming', 'Latest', 'Finished']"
                            clearable
                            hide-details
                            outlined
                            dense
                            class="pt-0 mb-3"
                        ></v-autocomplete>

                        <v-textarea
                            v-model="events.description"
                            outlined
                            auto-grow
                            rows="1"
                            row-height="15"
                            dense
                            label="Book Description"
                            hide-details
                            class="mb-3"
                        ></v-textarea>
                        <v-btn
                            color="primary"
                            block
                            elevation="0"
                            @click="InsertUpdateEvent()"
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
export default {
    props: ["value", "items", "title", "btn"],
    components: {
        InputText,
    },
    data: () => ({
        events: {
            id: null,
            title: null,
            subtitle: null,
            location: null,
            date: null,
            time: null,
            status: null,
            description: null,
            image: null,
        },

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],

        date: null,
        menu: false,

        image: null,
    }),

    methods: {
        HandleImage(event) {
            this.image = event;
            console.log(this.image);
        },

        InsertUpdateEvent() {
            let formData = new FormData();
            const config = {
                headers: {
                    "content-type": "multipart/form-data",
                },
            };

            if (this.btn == "Save") {
                formData.append("title", this.events.title);
                formData.append("subtitle", this.events.subtitle);
                formData.append("location", this.events.location);
                formData.append("date", this.events.date);
                formData.append("time", this.events.time);
                formData.append("status", this.events.status);
                formData.append("description", this.events.description);
                formData.append("image", this.image);

                axios
                    .post("/api/insert_event", formData, config)
                    .then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "You have successfully added new event!",
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        this.$emit("get-events");
                        this.showDialog = false;
                        this.image = null;
                        this.events = {};

                        // this.$refs.Insert.resetValidation();
                    })
                    .catch((error) => console.log("Error", error));
            } else {
                formData.append("id", this.events.id);
                formData.append("title", this.events.title);
                formData.append("subtitle", this.events.subtitle);
                formData.append("location", this.events.location);
                formData.append("date", this.events.date);
                formData.append("time", this.events.time);
                formData.append("status", this.events.status);
                formData.append("description", this.events.description);
                formData.append("image", this.image);

                axios
                    .post("/api/update_event", formData, config)
                    .then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "You have successfully updated new event!",
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        console.log(res.data);
                        this.$emit("get-events");
                        this.showDialog = false;
                        this.image = null;
                        this.events = {};
                        // this.$refs.Insert.resetValidation();
                    })
                    .catch((error) => console.log("Error", error));
            }
        },
    },

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
            if (this.btn != "Save") {
                console.log(this.items);
                this.events.id = this.items.id;
                this.events.title = this.items.title;
                this.events.subtitle = this.items.subtitle;
                this.events.location = this.items.location;
                this.events.date = this.items.date;
                this.events.time = this.items.time;
                this.events.status = this.items.status;
                this.events.description = this.items.description;
                this.image = this.items.image;
            }
        },
    },
};
</script>

<style lang="scss" scoped></style>
