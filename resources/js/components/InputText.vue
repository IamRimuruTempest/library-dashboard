<template>
    <div>
        <v-text-field
            v-model="inputVal"
            :label="label"
            :type="type"
            outlined
            hide-details
            dense
            class="mb-3"
            mask="###.###"
        ></v-text-field>
    </div>
</template>

<script>
export default {
    props: ["value", "label", "type"],
    computed: {
        inputVal: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit("input", val);
            },
        },
    },
};
</script>

<style lang="scss" scoped></style>
