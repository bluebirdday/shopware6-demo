import Plugin from 'src/plugin-system/plugin.class';
import StoreApiClient from 'src/service/store-api-client.service';
import LoadingIndicator from 'src/utility/loading-indicator/loading-indicator.util';

export default class AjaxPlugin extends Plugin {
    init() {
        this.el.innerHTML = LoadingIndicator.getTemplate();
        this.client = new StoreApiClient(window.accessKey);

        this.fetch();
    }

    fetch() {
        this.client.get('/store-api/deployment-info', (responseText) => {
            const responseData = JSON.parse(responseText);
            this.el.innerHTML = responseData.branch;
        });
    }
}
