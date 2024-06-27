<template>
    <v-list elevation="0" :bg-color="color">
        <v-list-item v-if="!categories || categories.length === 0">
            no categories found
        </v-list-item>
        <v-list-item v-else v-for="category in categories" :key="category.id">
          
            <v-btn variant="text" size="small"
                :to="{ path: `/subcategories/all/${category.id}`, query: { customer: this.user } }">
                <span>
                    <v-avatar color="surface-variant" size="28px">
                        <v-img v-if="category.thumbnail" :src="`./storage/Categories/Thumbnails/${category.thumbnail}`"
                            :lazy-src="`./storage/Categories/Thumbnails/${category.thumbnail}`"
                            :alt="`${category.name}`"></v-img>
                    </v-avatar>
                </span>
                <!-- <v-icon v-else :icon="category.icon"></v-icon> -->
                <span>{{ category.name }}</span>
            </v-btn>
            
        </v-list-item>
    </v-list>
</template>

<script>
export default {
    name: 'CategoriesList',
    props: ['categories', 'color'],
}
</script>