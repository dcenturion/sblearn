
const chai = require('chai');

const jsdom = require("jsdom");
const { JSDOM } = jsdom;
let dom = new JSDOM('<!doctype html><html><body><div id="auto-carusel"><div><img src="http://www.fotospanoramicasdechile.cl/wp-content/uploads/2016/07/AmanecerCordilleradelPaine.jpg"/></div></div></body></html>');
const { window } = dom;
global.document = dom;
global.window = window;
global.navigator = {
    userAgent: 'node.js',
};

var $ = require('jquery');
var expect = require('chai').expect;
const carruselAutomaticDom = require('../assets/js/carrusel-automatico');

describe("Comprobar extensión", () => {

	it("Comprobar .jpg, .png", () => {
		expect(carruselAutomaticDom.extension, true)
	})
})

describe("Comprobar id", () => {

  it("Existe el id", () => {
    chai.assert.exists($('#auto-carusel'));
  })
})