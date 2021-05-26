<?php
namespace OmniPro\Nmi\Gateway\Validator;

use \Magento\Payment\Gateway\Validator\ResultInterfaceFactory;

class ResponseCodeValidator extends \Magento\Payment\Gateway\Validator\AbstractValidator
{
    const NMI_APPROVED = 1;
    const NMI_DECLINED = 2;
    const NMI_ERROR = 3;

    /**
     * @var ResultInterfaceFactory
     */
    private $resultInterfaceFactory;

    /**
     * @param \Magento\Payment\Gateway\Helper\SubjectReader
     */
    private $subjectReader;

    /**
     * @param ResultInterfaceFactory $resultFactory
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        \Magento\Payment\Gateway\Helper\SubjectReader $subjectReader
    ) {
        $this->resultInterfaceFactory = $resultFactory;
        $this->subjectReader = $subjectReader;
    }

    public function validate(array $validationSubject) {
        $response = $this->subjectReader->readResponse($validationSubject);
        if(isset($response['response'])) {
            switch ($response['response']) {
                case self::NMI_APPROVED:
                    return $this->createResult(true, []);
                case self::NMI_DECLINED:
                case self::NMI_ERROR:
                    return $this->createResult(false, [
                        [__($response['responsetext'])]
                    ]);
            }
        }

        return $this->createResult(false, [__("Ha ocurrido un error procesando la transacción")]);
    }
}