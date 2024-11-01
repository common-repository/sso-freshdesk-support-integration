<?php

namespace MSFSI\Helper\Factory;

interface RequestHandlerFactory
{
	public function generateRequest();

	public function getRequestType();
}