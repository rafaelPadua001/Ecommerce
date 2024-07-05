<template>
    <v-container>
        <v-sheet class="py-2 px-2" :color="color ">
            <v-menu :activator="$refs.menuBtn" open-on-hover>
                <template v-slot:activator="{ props }">
                    <v-btn ref="menuBtn" variant="plain" size="small" v-bind="props">
                        <!-- <v-icon icon="fa-solid fa-grip-vertical fa-2xs">
                                </v-icon> -->
                        categories
                        <template v-slot:append>
                            <v-icon icon="fa-solid fa-chevron-down fa-2xs"></v-icon>
                        </template>


                    </v-btn>
                </template>
                <CategoriesList
                    :categories="this.categories"
                    :color="this.color"
                ></CategoriesList>
            </v-menu>
        </v-sheet>
    </v-container>

</template>

<script>
import CategoriesList from './partials/CategoriesList.vue';

export default {
    name: 'Categories',
    components: {CategoriesList},
    props: ['categories', 'color'],
    data: () => ({

    }),
    methods: {

    },
}
</script>
<style>
.custom-menu {
    position: absolute;
    top: calc(var(--v-toolbar-height) + 10px);
    /* altura da app-bar + espaço extra */
    z-index: 9999;
    /* ou um valor maior do que o z-index da app-bar */
}
</style>