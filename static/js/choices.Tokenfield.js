class Tokenfield {
    constructor(element, options = {}) {
		if (typeof element === 'string') {
            element = document.getElementById(element);
        }
		
		if (!element) {
            throw new Error('Element not found');
        }
		
        this.element = element;
        this.options = options;
        this.init();
    }

    init() {
		let elementValue = this.element.value || '';
		let oldValues = elementValue.split(',').map(value => value.trim()).filter(value => value.length > 0);

        let choicesItems = oldValues.map(value => ({
            value: value,
            label: value
        }));

        this.element.value = '';

        this.choices = new Choices(this.element, {
            items: choicesItems,
            ...this.options
        });

        this.choices.setValue(oldValues);
    }
}