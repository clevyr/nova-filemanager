import IndexField from './field/IndexField'
import DetailField from './field/DetailField'
import FormField from './field/FormField'

Nova.booting((app) => {
    app.component('index-filemanager-field', IndexField);
    app.component('detail-filemanager-field', DetailField);
    app.component('form-filemanager-field', FormField);
});
