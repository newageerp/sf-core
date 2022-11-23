import React, { useState, Fragment, } from "react";
import { Popover } from "react-tiny-popover";
import OldButton, { ButtonBgColor, ButtonSize } from "./OldButton";
import { useTranslation } from 'react-i18next';

interface Props {
  onClick: () => void;
  children: any;
  customText?: string | React.ReactElement;
  confirmText?: string;
  cancelText?: string;
  hideConfirm?: boolean;
  // parentElement: any;
}

interface CompProps {
  onClick: () => void,
  children: any,
}

export default function OldPopoverConfirm(props: Props) {
  const [isPopoverOpen, setIsPopoverOpen] = useState(false);
  const togglePopoverOpen = () => {
    setIsPopoverOpen(!isPopoverOpen);
  };

  const CustomComponent = React.forwardRef<HTMLDivElement, CompProps>((props, ref) => (
    React.cloneElement(props.children, {
      onClick: props.onClick, forwardRef: ref, ref: ref
    })
  ));
  const { t } = useTranslation();

  const content = (
    <Fragment>
      <div className={"p-2 rounded-md shadow-md space-y-2 bg-white"}>
        <p className={"text-sm"}>{props.customText ? props.customText : t("Ar Jūs įsitikinęs?")}</p>
        <div className={"grid grid-cols-2 gap-2"}>
          {!props.hideConfirm &&
            <OldButton
              onClick={() => {
                togglePopoverOpen();
                props.onClick();
              }}
              size={ButtonSize.sm}
            >
              {props.confirmText ? props.confirmText : t("Taip")}
            </OldButton>
          }
          <OldButton
            onClick={togglePopoverOpen}
            size={ButtonSize.sm}
            bgColor={ButtonBgColor.yellow}
          >
            {props.cancelText ? props.cancelText : t("Ne")}
          </OldButton>

        </div>
      </div>
    </Fragment>
  );

  return (
    <Popover
      isOpen={isPopoverOpen}
      positions={["bottom", "top", "left", "right"]} // preferred positions by priority
      content={content}
      containerClassName={"z-250"}
    // parentElement={props.parentElement}
    >
      <CustomComponent onClick={() => setIsPopoverOpen(!isPopoverOpen)}>
        {props.children}
      </CustomComponent>
    </Popover>
  );
}
