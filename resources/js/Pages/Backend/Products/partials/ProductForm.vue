<template>
    <v-card class="mx-auto">
        <v-card-title>
            <span class="text-h5">{{ formTitle }}</span>
        </v-card-title>

        <v-divider></v-divider>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col>
                        <h5>Product</h5>
                    </v-col>
                </v-row>

                <v-divider></v-divider>
                <v-spacer></v-spacer>

                <v-row>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.name" label="Product name"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-select v-model="editedItem.category_id" label="Category" :items="categories"
                            item-title="name" item-value="id"></v-select>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-select v-model="editedItem.subcategory_name" label="Subcategory" :items="subcategories"
                            item-title="name" item-value="id"></v-select>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-col cols="auto" sm="6" md="12">
                            <v-textarea clearable v-model="editedItem.description" label="Description"
                                variant="solo-filled"></v-textarea>
                        </v-col>
                    </v-col>
                    <v-col cols="auto" sm="4" md="4">
                        <v-text-field v-model="editedItem.price" label="Price" prefix="R$ " v-bind="config">
                        </v-text-field>

                    </v-col>
                    <v-col cols="auto" sm="4" md="4">
                        <v-text-field v-model="editedItem.stock_quantity" label="Quantity"></v-text-field>
                    </v-col>
                </v-row>


                <v-row>
                    <v-col>
                        <h5>Midias</h5>
                    </v-col>
                </v-row>

                <v-divider></v-divider>
                <v-spacer></v-spacer>

                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-file-input v-model="editedItem.images" @change="previewImages" multiple chips></v-file-input>
                        <div v-if="previewImages">
                            <v-row v-if="images">
                                <v-col v-for="(image, index) in images" :key="index" cols="12" md="6">
                                    <v-card>
                                        <v-img v-model="images" :src="image.src" :lazy-src="image.name"
                                            :alt="'Image ' + index" cover>
                                            <div float="right" justify="center">
                                                <v-btn icon="fas fa-close" size="12px" density="compact"
                                                    @click="removeImagePreview">
                                                    <v-icon icon="fas fa-close" size="10px"></v-icon>

                                                </v-btn>
                                            </div>

                                        </v-img>

                                    </v-card>
                                </v-col>
                                <v-divider></v-divider>
                                <v-spacer></v-spacer>
                                <v-spacer></v-spacer>
                            </v-row>
                            <v-row v-if="images.length == 0">
                                <v-col v-for="(imageName, index) in editedItem.images" :key="index">
                                    <v-img :src="`./storage/products/${imageName}`" max-width="100"
                                        max-height="100"></v-img>
                                </v-col>
                            </v-row>
                        </div>
                        <div v-else>
                            <v-row v-if="!previewImages">

                                <v-col v-for="(image, index) in editedItem.images" :key="index" cols="12" md="6">
                                    <v-card>

                                        <v-img v-model="editedItem.images" :src="'./storage/uploads/' + image"
                                            :lazy-src="image" :alt="'Image ' + index" cover>
                                            <div float="right" justify="center">
                                                <v-btn icon="fas fa-close" size="12px" density="compact"
                                                    @click="removeImagePreviewEdit(index)">
                                                    <v-icon icon="fas fa-close" size="10px"></v-icon>

                                                </v-btn>
                                            </div>

                                        </v-img>

                                    </v-card>


                                </v-col>
                                <v-divider></v-divider>
                                <v-spacer></v-spacer>
                                <v-spacer></v-spacer>
                            </v-row>

                        </div>

                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.platform" label="Platform  (Ex:youtube)"></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.video_link" label="Videos" hint="www.youtube.com/watch?v="
                            persistent-hint @change="preview">
                            <template v-slot:append>
                                <v-btn @click="preview" density="compact" icon size="x-small">
                                    <v-icon icon="fas fa-upload fa-2xs">

                                    </v-icon>
                                </v-btn>
                            </template>
                        </v-text-field>

                        <v-card>
                            <template v-slot:append>
                                <v-btn @click="removeVideoPreview" density="compact" icon size="x-small">
                                    <v-icon icon="fas fa-close fa-2xs"></v-icon>
                                </v-btn>
                            </template>
                            <iframe id="ytplayer" type="text/html" :src="`https://www.youtube.com/embed/${previewLink}`"
                                frameborder="0" allowfullscreen autoplay="1" controls="0" fs="0" color="white"
                                v-if="editedItem.video_link" cover>
                            </iframe>
                        </v-card>

                    </v-col>

                </v-row>


                <v-divider></v-divider>
                <v-spacer></v-spacer>
                <v-row>
                    <v-col>
                        <h5>Variants</h5>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="auto" md="4" sm="2">
                        <h5>Select Colors:</h5>

                        <v-color-picker v-model="editedItem.colors" class="ma-2" show-swatches
                            swatches-max-height="75px" width="90%" v-bind:onChange="selectedColor()">

                        </v-color-picker>


                    </v-col>
                    <v-row v-if="colors !== 0 && colors !== 'null'" fluid>
                        <v-col>
                            <v-row v-for="(color, index) in colors" :key="index" cols="auto" align="center">
                                <v-col cols="auto" sm="2">
                                    <v-chip :color="color" variant="elevated">

                                    </v-chip>

                                    <!-- <v-card :color="color">
                                      <template v-slot:append>
                                        <v-btn icon density="compact" size="small" @click="removeSelectedColor(index)">
                                          <v-icon icon="fas fa-close fa-2xs"></v-icon>
                                        </v-btn>
                                      </template>
                                    </v-card> -->
                                </v-col>
                                <v-col cols="4" sm="4">
                                    <v-text-field v-model="color_qty[index]" label="Quantity" type="number"
                                        :prefix="color" :color="color"></v-text-field>
                                </v-col>
                                <v-col cols="auto" sm="2">
                                    <v-btn :color="color" icon density="compact" size="x-small"
                                        @click="removeSelectedColor(index)">
                                        <v-icon icon="fas fa-close fa-2xs"></v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>

                </v-row>

                <v-spacer></v-spacer>
                <v-divider></v-divider>

                <v-row>
                    <v-col cols="auto" md="4" sm="2">
                        <label>Sizes: </label>
                        <v-select v-model="editedItem.size" chips label="Sizes" :items="['P', 'M', 'G', 'GG', 'XG']"
                            multiple variant="underlined"></v-select>
                    </v-col>
                    <v-col cols="auto" md="4">
                        <v-row v-for="(size, index) in editedItem.size" :key="index">
                            <v-col cols="auto" sm="4">
                                <v-chip color="success" variant="elevated">{{ size }}</v-chip>

                            </v-col>
                            <v-col cols="auto">
                                <v-text-field v-model="size_qty[index]" label="Quantity" type="number"></v-text-field>
                            </v-col>

                        </v-row>

                    </v-col>

                </v-row>
                
               
                <v-row>
                    <v-col>
                        <h5>Scales</h5>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-select v-model="editedItem.unity" chips label="Unidade" :items="['cm', 'mm', 'und', 'pcs']"
                            variant="underlined"></v-select>
                    </v-col>

                </v-row>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.weight" label="Weight" suffix="Kg"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.height" label="Height"
                            :suffix="this.editedItem.unity"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.width" label="Width"
                            :suffix="this.editedItem.unity"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.length" label="Length"
                            :suffix="this.editedItem.unity"></v-text-field>
                    </v-col>

                </v-row>
                <v-row>
                    <v-col>
                        <h5>Discount</h5>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-select v-model="editedItem.discount_id" :items="discounts" density="compact"
                            label="Select Discount" item-title="code" item-value="id"></v-select>
                    </v-col>
                    <v-col>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field v-model="editedItem.sku" label="Sku"></v-text-field>
                        </v-col>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <h5>Seo </h5>
                    </v-col>
                </v-row>

                <v-divider></v-divider>
                <v-spacer></v-spacer>

                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.meta_name" label="Meta Name"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.meta_key" label="Meta Key"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-textarea clearable v-model="editedItem.meta_description" label="Meta Description"
                            prepend-icon="$vuetify" variant="solo-filled"></v-textarea>
                    </v-col>
                </v-row>
                <v-row>

                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.slug" label="Slug"></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <h5>Settings</h5>
                    </v-col>
                </v-row>

                <v-divider></v-divider>
                <v-spacer></v-spacer>

                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        <v-switch v-model="editedItem.highlights" label="Highlight" color="success"
                            @click="highlights_switch" :value="this.highlights"></v-switch>

                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-switch v-model="editedItem.availability" label="Availability" color="success"
                            @click="availability_switch" :value="this.availability"></v-switch>
                    </v-col>

                    <v-col cols="12" sm="6" md="4">
                        <v-switch v-model="editedItem.status" label="Status" color="success"
                            @click="status_switch"></v-switch>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">

                        <v-switch v-model="editedItem.launch" label="Launch product" color="success"
                            @click="lauch_switch" :value="this.launch"></v-switch>
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>

        <v-spacer></v-spacer>
        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue-darken-1" variant="text" @click="close">
                Cancel
            </v-btn>
            <v-btn color="blue-darken-1" variant="text" @click="save">
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: 'ProductForm',
    props: [
        'editedItem',
        'highlights',
        'availability',
        'status',
        'launch',
        'categories',
        'subcategories',
        'colors',
        'color_qty',
        'size_qty'
    ],
    data: () => ({
        // highlights: false,
        // availability: false,
        // status: false,
        //launch: false,
        images: [],
    }),
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
        config() {
            return {
                decimal: ",",
                thousands: ".",
                prefix: "R$ ",
                suffix: "",
                precision: 2,
                masked: false
            };
        },
    },
    methods: {
        handleFiles() {
            this.$refs.images[0];
        },
        previewImages(event) {
            const files = event.target.files;
            if (files) {
                this.images = [];
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    const file = files[i];

                    reader.onload = (e) => {
                        this.images.push({
                            src: e.target.result,
                            name: file.name,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            }
        },
        onPriceInput(value) {

        },
        removeImagePreview(index) {
            return this.images.splice(index, 1);
        },
        removeImagePreviewEdit(index) {
            return this.editedItem.images.splice(index, 1);
        }, preview() {
            return this.previewLink = this.editedItem.video_link.slice(32);
        },
        removeVideoPreview() {
            return this.editedItem.video_link = "";
        },
        selectedColor() {
            let selected_colors = this.editedItem.colors;
            this.colors.push(selected_colors);
        },
        removeSelectedColor(index) {
            return this.colors.splice(index, 1);
        },
        highlights_switch() {
            if (this.editedItem.highlights) {
                this.editedItem.highlights = 0;
                return this.$emit('hl_turn', this.editedItem.highlights);
            }

            this.editedItem.highlights = 1;
            return this.$emit('hl_turn', this.editedItem.highlights);
        },
        availability_switch() {
            if (this.editedItem.availability) {
                this.editedItem.availability = 0;
                return this.$emit('av_turn', this.editedItem.availability);
            }
            this.editedItem.availability = 1;
            return this.$emit('av_turn', this.editedItem.availability);
        },
        status_switch() {
            if (this.editedItem.status) {
                this.editedItem.status = 0;
                return this.$emit('st_turn', this.editedItem.status);
            }
            this.editedItem.status = 1;
            return this.$emit('st_turn', this.editedItem.status);
        },
        lauch_switch() {
            if (this.editedItem.launch) {
          
                this.editedItem.launch = 0;
                return this.$emit('lc_turn', this.editedItem.launch);
            }
    
            this.editedItem.launch = 1;
            return this.$emit('lc_turn', this.editedItem.launch);
        },
        save(){
            this.editedItem.images = this.images;
            return this.$emit('save', this.editedItem);
        }
    }
}
</script>