
const chai = require('chai');

const jsdom = require("jsdom");
const { JSDOM } = jsdom;
let dom = new JSDOM('<!doctype html><html><body><ul class="list-images"><li><img src="http://thewallpaper.co/wp-content/uploads/2016/10/Full-wallpapers-desktop-desktop-images-windows-10-backgrounds-colourful-hi-res-quality-images-computer-wallpapers-best-artwork-1920x1200.jpg"></li></ul><ol class="navegation"><li class="left iconsDefaultColor"><span class="fa fa-chevron-left></span></li><li class="right iconsDefaultColor"><span class="fa fa-chevron-right"></span></li></body></html>');
const { window } = dom;
global.document = dom;
global.window = window;
global.navigator = {
    userAgent: 'node.js',
};

var $ = require('jquery');
var expect = require('chai').expect;
const carruselNavegationDom = require('../assets/js/carrusel-navegation');

describe("Comprobar extensión", () => {
  
  it("Comprobar .jpg, .png", () => {
    expect(carruselNavegationDom.extension,true)
  })

describe("Comprobar clases", () => {

  it("Existe las clases", () => {
    chai.assert.exists($('.list-images'));
    chai.assert.exists($('.navegation'));
  })

})
});