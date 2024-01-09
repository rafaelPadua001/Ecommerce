<template>
    <v-bottom-sheet inset>
        <v-list>
            <v-list-subheader>
              Share
            </v-list-subheader>
            <v-list-item
               
            >
                <v-row>
                    <v-col  v-for="(icon, index) in icons"
                    :key="index">
                    <v-btn-group>
                        <v-btn icon variant="flat" @click="share(index, icon)">
                            <v-icon :icon="icon"></v-icon>
                        </v-btn>
                    </v-btn-group>
                    </v-col>
                </v-row>
            </v-list-item>
        </v-list>
    </v-bottom-sheet>
</template>

<script>
    export default {
        props: ['icons'],
        data: () => ({
            iconIndex: 0,
            iconItem: false,
        }),
        methods: {
            share(index, icon){
                this.iconIndex = this.icons.indexOf(index);
                this.iconItem = icon;
                console.log(this.iconItem);
            }
        }
    }
 
</script>