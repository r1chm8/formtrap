<template>
    <div class="relative bg-white shadow px-4 py-6 md:p-8">
        <template v-if="! isLoading('primary.form-fields')">
            <div class="flex items-center justify-between">
                <h1 class="title">Overview</h1>

                <div class="flex items-center ml-4">
                    <notifications />

                    <div class="field addons ml-2">
                        <div class="control">
                            <router-link
                                :to="{
                                    name: 'forms.fields',
                                    params: {
                                        id: form.id,
                                        versionId: form.version.id
                                    }
                                }"
                                class="button"
                            >Edit Form</router-link>
                        </div>

                        <div class="control">
                            <dropdown class="right">
                                <div slot="button" class="border-right border-purple-600 button px-3 text-white">
                                    <span class="icon">
                                        <icon icon="caret-down"></icon>
                                    </span>
                                </div>

                                <a
                                    class="dropdown-item"
                                    @click="createNewVersion"
                                >Create new version</a>

                                <div class="dropdown-divider"></div>

                                <router-link
                                    :to="{ name: 'forms.manage.versions' }"
                                    class="dropdown-item"
                                >Manage versions</router-link>
                            </dropdown>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 mb-8">
                <hr />
            </div>

            <div class="mb-2 md:max-w-xs">
                <label for="share" class="label">Share this form</label>

                <div class="control control-icon-right">
                    <vue-input
                        id="share"
                        ref="shareUrl"
                        :value="form.endpoint"
                        readonly
                    ></vue-input>
            
                    <a class="icon bg-purple-200" @click="copyShareUrl">
                        <icon :icon="['far', 'copy']"></icon>
                    </a>
                </div>
            </div>

            <div class="mb-8">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur semper dolor metus.
            </div>

            <!-- <notification-email
                class="mb-2 md:max-w-md"
                method="patch"
                :action="`/api/forms/${form.id}`"
            ></notification-email>

            <div>
                Enter the email address you would like notifications to be sent to, leave this field blank if you do not wish
                to recieve notifications
            </div>

            <hr class="my-8"> -->

            <h2 class="subtitle mb-1">Integrate</h2>

            <div class="mb-6">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur semper dolor metus.
            </div>

            <tabs>
                <tab name="HTML Form">
                    <div class="tab p-4 md:p-6">
                        <div class="content mb-4">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed condimentum vehicula nisi,
                                vel porttitor elit volutpat non. Quisque tincidunt <a href="">massa ut gravida</a> luctus.
                            </p>
                        </div>
                        
                        <vue-pre>{{ getHtmlCode() }}</vue-pre>
                    </div>
                </tab>

                <tab name="Ajax">
                    <div class="tab p-4 md:p-6">
                        <div class="content mb-4">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed condimentum vehicula nisi,
                                vel porttitor elit volutpat non. Quisque tincidunt <a href="">massa ut gravida</a> luctus.
                            </p>
                        </div>
                        
                        <vue-pre>{{ getAjaxCode() }}</vue-pre>
                    </div>
                </tab>
            </tabs>
        </template>

        <loader class="bg-grey-200 -mx-1"></loader>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import Notifications from './partials/Notifications';
    // import NotificationEmail from './partials/NotificationEmail';

    import Dropdown from '@js/components/ui/Dropdown';
    import Tab from '@js/components/ui/Tab';
    import Tabs from '@js/components/ui/Tabs';
    import VueInput from '@js/components/form/Input';
    import VuePre from '@js/components/ui/Pre';

    export default {
        components: {
            Notifications,
            Dropdown,
            Tab,
            Tabs,
            VueInput,
            VuePre
        },

        computed: {
            ...mapGetters({
                form: 'form/data',
                fields: 'form/fields',
                isLoading: 'loader/isLoading'
            }),

            loadingForm() {
                return this.isLoading('primary.form');
            }
        },

        watch: {
            loadingForm: {
                handler(loading) {
                    if (! loading) {
                        this.fetchFields({
                            formId: this.form.id,
                            versionId: this.form.version.id
                        });
                    }
                },
                immediate: true
            }
        },

        created() {
            this.startLoading('primary.form-fields');
        },
        
        methods: {
            ...mapActions({
                fetchFields: 'form/fetchFields'
            }),

            createNewVersion() {
                axios.post(`/api/forms/${this.form.id}/versions`, {
                        duplicate_of: this.form.version.id
                    })
                    .then(response => {
                        let version = response.data.data;

                        this.$router.push({
                            name: 'forms.fields',
                            params: {
                                id: this.form.id,
                                versionId: version.id
                            }
                        });
                    });
            },
            
            copyShareUrl() {
                this.$refs.shareUrl.$el.select();
                document.execCommand('copy');
                window.getSelection().removeAllRanges();

                this.$toasted.success('Share url successfully copied', {
                    duration: 1500
                });
            },

            generateOptionId(option) {
                return option.value || option.label.toLowerCase()
                    .replace(/[^\w\s_]/g, '')
                    .replace(/[\s-]+/g, '_')
                    .replace(/^_+|_+$/g, '');
            },

            getHtmlCode() {
                let code = '';
                let tab = '  ';

                code = `<form action="${this.form.endpoint}" method="post">\n`;

                this.fields.forEach(field => {
                    code += `${ tab }<div>\n`;

                    switch (field.type_id) {
                        case 1: // Text
                            code += `${ tab + tab }<label for="${ field.name }">${ field.label }${ field.required ? ' *' : '' }</label>\n`
                            code += `${ tab + tab }<input id="${ field.name }" type="text" name="${ field.name }"${ field.required ? ' required' : '' }>\n`;
                            break;

                        case 2: // Textarea
                            code += `${ tab + tab }<label for="${ field.name }">${ field.label }${ field.required ? ' *' : '' }</label>\n`
                            code += `${ tab + tab }<textarea id="${ field.name }" name="${ field.name }"${ field.required ? ' required' : '' }></textarea>\n`;
                            break;

                        case 3: // Radio
                            code += `${ tab + tab }<div>${ field.label }${ field.required ? ' *' : '' }</div>\n`

                            field.options.forEach((option, index) => {
                                let optionId = this.generateOptionId(option);

                                code += `${ tab + tab }<div>\n`;
                                code += `${ tab + tab + tab }<input id="${ field.name }_${ optionId }" type="radio" name="${ field.name }" value="${ option.value || option.label }"${ field.required ? ' required' : '' }>\n`;
                                code += `${ tab + tab + tab }<label for="${ field.name }_${ optionId }">${ option.label }</label>\n`
                                code += `${ tab + tab }</div>\n${ field.options.length - 1 !== index ? '\n' : '' }`;
                            });
                            break;

                        case 4: // Checkboxes
                            code += `${ tab + tab }<div>${ field.label }${ field.required ? ' *' : '' }</div>\n`

                            field.options.forEach((option, index) => {
                                let optionId = this.generateOptionId(option);
                                
                                code += `${tab + tab}<div>\n`;
                                code += `${ tab + tab + tab }<input id="${ field.name }_${ optionId }" type="checkbox" name="${ field.name }" value="${ option.value || option.label }"${ field.required ? ' required' : '' }>\n`;
                                code += `${ tab + tab + tab }<label for="${ field.name }_${ optionId }">${ option.label }</label>\n`
                                code += `${ tab + tab }</div>\n${ field.options.length - 1 !== index ? '\n' : '' }`;
                            });
                            break;

                        case 5: // Select
                            code += `${ tab + tab }<label for="${ field.name }">${ field.label }${ field.required ? ` *` : `` }</label>\n`
                            code += `${ tab + tab }<select id="${ field.name }" name="${ field.name }"${ field.multiple ? ' multiple' : '' }${ field.required ? ' required' : '' }>\n`;
                            code += `${ tab + tab + tab }<option selected>Please select</option>\n`;
                            field.options.forEach(option => {
                                code += `${ tab + tab + tab }<option value="${ option.value || option.label }">${ option.label }</option>\n`;
                            });
                            code += `${ tab + tab }</select>\n`;
                            break;
                    }

                    code += `${ tab }</div>\n\n`
                });

                code += `${ tab }<div>\n`;
                code += `${ tab + tab }<button type="submit">Submit</button>\n`;
                code += `${ tab }</div>\n`;

                code += `</form>`;

                return code;
            },

            getAjaxCode() {
                let code = '';
                let tab = '  ';

                code = `axios.post('${this.form.endpoint}', {\n`;

                this.fields.forEach(field => {
                    switch (field.type_id) {
                        case 1: // Text
                        case 2: // Textarea
                        case 3: // Radio
                            code += `${ tab + tab }${field.name}: ''\n`;
                            break;
                            
                        case 4: // Checkboxes
                            code += `${ tab + tab }${field.name}: []\n`;
                            break;

                        case 5: // Select
                            code += `${ tab + tab }${field.name}: ${field.multiple ? '[]' : '\'\'' }\n`;
                            break;
                    }
                });

                code += `${ tab }})\n`;
                code += `${ tab }.then(response => {\n`;
                code += `${ tab + tab }console.log(response);\n`;
                code += `${ tab }})\n`;
                code += `${ tab }.catch(error => {\n`;
                code += `${ tab + tab }console.log(error);\n`;
                code += `${ tab }});\n`;

                return code;
            }
        }
    }
</script>
