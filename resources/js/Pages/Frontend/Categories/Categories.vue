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