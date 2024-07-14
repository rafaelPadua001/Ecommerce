<template>
    {{ name }}
    <v-list>
        <v-list-item-group v-for="(productName, idx) in name.split('  ')" :key="idx">
            <v-list-item :key="index">
                <v-card class="mx-1 my-1" :max-width="500">
                    <v-toolbar class="bg-transparent">
                        <v-toolbar-title>{{ productName }}</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <template v-slot:append>

                            <v-btn icon variant="plain" :id="'menu-activator-' + index">
                                <v-icon icon="fas fa-ellipsis-vertical"></v-icon>
                            </v-btn>

                            <v-menu :activator="'#menu-activator-' + index">
                                <v-list>
                                    <v-list-item>
                                        <v-btn icon variant="plain">
                                            <v-icon icon="fas fa-trash fa-2xs" @click="deleteGaleryDialog(productName)">
                                            </v-icon>

                                        </v-btn>
                                    </v-list-item>
                                </v-list>
                            </v-menu>

                            <v-dialog v-model="deleteGalery" :key="'dialog-' + index">
                                <v-card class="d-flex justify-center mx-auto mb-6">
                                    <v-card-text>
                                        <p>Tem certeza que quer remover as imagens dessa galeria
                                            ?
                                            {{ image.product_name }}
                                        </p>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-btn-goup>
                                            <v-btn variant="plain" @click="deleteGaleryConfirm">
                                                Remover
                                            </v-btn>
                                            <v-btn variant="plain" @click="closeGaleryDialog">
                                                Cancelar
                                            </v-btn>
                                        </v-btn-goup>
                                    </v-card-actions>
                                </v-card>

                            </v-dialog>
                        </template>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-row justify="center" no-gutters>
                            <v-col class='d-flex flex-child flex-column' cols="auto" v-for="(image, index) in images"
                                :key="index">
                                <v-hover v-slot="{ isHovering, props }">
                                    <v-card class="d-flex align-center mx-auto flex-column" v-bind="props">
                                        <v-img v-if="image.product_name === productName"
                                            :src="'storage/products/' + image.name + '.' + image.extension"
                                            :lazy-src="'./storage/products/' + image.name + '.' + image.extension"
                                            :alt="productName"
                                            class="align-start" gradient="to bottom, rgba(0, 0, 0, .1), rgba(0,0,0,.5)"
                                            :width="150" aspect-ratio="1" cover
                                        >
                                            <v-expand-transition>
                                                <div v-if="isHovering"
                                                    class="d-flex transition-fast-in-fast-out bg-grey-darken-4 v-card-img--reveal text-2"
                                                    style="height: 100%; width: 100%;">

                                                    <v-btn-group color="grey-lighten-2">
                                                        <v-btn class="mb-2" icon variant="plain"
                                                            @click="previewDialog(image)" size="small">
                                                            <v-icon icon="fas fa-eye"></v-icon>
                                                        </v-btn>
                                                        <v-btn icon variant="plain" class="mb-2" size="small">
                                                            <v-icon icon="fa-solid fa-trash" @click="deleteItem(image)">
                                                            </v-icon>
                                                        </v-btn>
                                                    </v-btn-group>

                                                    <div>
                                                        <v-dialog v-model="preview" :width="900" :height="720"
                                                            class="d-flex justify-center mb-2 mx-auto flex-column">
                                                            <v-card>
                                                                <v-text>
                                                                    <v-img class="mx-auto fill-height"
                                                                        aspect-ratio="4/3" cover
                                                                        :src="`./storage/products/${this.editedItem.name}.${this.editedItem.extension}`"
                                                                        :lazy-src="`./storage/products/${this.editedItem.name}.${this.editedItem.extension}`">

                                                                        <v-spacer></v-spacer>

                                                                        <v-btn color="primary" icon id="menu-activator"
                                                                            @click="preview = false" size="x-small">
                                                                            <v-icon icon="fas fa-close"></v-icon>
                                                                        </v-btn>
                                                                        <template v-slot:placeholder>
                                                                            <div
                                                                                class="d-flex align-center justify-center fill-height">
                                                                                <v-progress-circular
                                                                                    color="grey-lighten-4"
                                                                                    indeterminate>
                                                                                </v-progress-circular>
                                                                            </div>
                                                                        </template>

                                                                    </v-img>
                                                                </v-text>

                                                            </v-card>
                                                        </v-dialog>
                                                    </div>
                                                </div>
                                            </v-expand-transition>
                                        </v-img>
                                    </v-card>
                                </v-hover>

                                
                            </v-col>
                        </v-row>

                    </v-card-text>
                </v-card>

            </v-list-item>
        </v-list-item-group>
    </v-list>
</template>

<script>
export default {
    name: 'ProductImageList',
    props: ['images', 'name', 'index'],
   
    methods: {
        deleteGaleryDialog(image){
            return this.$emit('delete-galery-dialog', image);
        },
       
    }
}
</script>