<template>
    <v-card class="mx-auto">
        <v-card-title>Categories</v-card-title>
        <v-divider></v-divider>
        <v-card-text v-if="category">
            <v-row>
                <v-col class="d-flex flex-column" cols="auto" sm="2">

                    <v-btn class="mr-2" size="small" variant="flat">
                        <span>
                            <v-avatar size="26">
                                <v-img v-if="category.thumbnail"
                                    :src="`./storage/Categories/Thumbnails/${category.thumbnail}`"
                                    :lazy-src="`./storage/Categories/Thumbnails/${category.thumbnail}`"
                                    :alt="`${category.name}`"></v-img>
                            </v-avatar>
                        </span>
                        {{ category.name }}
                    </v-btn>

                </v-col>
                <v-divider></v-divider>
            </v-row>
        </v-card-text>

        <v-card-text v-if="subcategories">
            <h5>Subcategories</h5>
            <v-row v-for="(subcategory, index) in subcategories" :key="index">
                <v-col class="d-flex justify-start flex-row">

                    <v-btn class="mr-2" size="small" variant="flat" @click="getProductSubcategory(subcategory)">
                        {{ subcategory.name }}
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>

        <v-card-text>
            <FilterPrice 
                :products="this.products"
            />
        </v-card-text>

    </v-card>

</template>

<script>
import FilterPrice from '../../RangeProductPrice/RangeSliderPrice.vue';
export default {
    name: 'CategoriesCard',
    props: ['category', 'subcategories', 'products'],
    components: {FilterPrice},
}
</script>