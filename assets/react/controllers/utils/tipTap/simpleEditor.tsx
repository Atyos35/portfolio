import { useCallback, useEffect, useRef } from "react";
import classNames from "classnames";
import { useEditor, EditorContent } from "@tiptap/react";
import StarterKit from "@tiptap/starter-kit";
import TextStyle from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';

import * as Icons from "./icon";

type SimpleEditorProps = {
  value: string;
  onChange: (value: string) => void;
};

export function SimpleEditor({ value, onChange }: SimpleEditorProps) {
  const colorInputRef = useRef<HTMLInputElement>(null);

  const editor = useEditor({
    extensions: [
      StarterKit,
      TextStyle,
      Color,
    ],
    content: value,
    onUpdate({ editor }) {
      onChange(editor.getHTML());
    },
  });

  const toggleBold = useCallback(() => {
    editor?.chain().focus().toggleBold().run();
  }, [editor]);

  const toggleItalic = useCallback(() => {
    editor?.chain().focus().toggleItalic().run();
  }, [editor]);

  const toggleBulletList = useCallback(() => {
    editor?.chain().focus().toggleBulletList().run();
  }, [editor]);

  const openColorPicker = useCallback(() => {
    colorInputRef.current?.click();
  }, []);

  const applyColor = useCallback((event: React.ChangeEvent<HTMLInputElement>) => {
    const color = event.target.value;
    editor?.chain().focus().setColor(color).run();
  }, [editor]);

  useEffect(() => {
    if (editor && value !== editor.getHTML()) {
      editor.commands.setContent(value, false);
    }
  }, [value, editor]);

  if (!editor) return null;

  return (
    <div className="editor">
      <div className="menu">
        <span
          className={classNames("menu-button", { disabled: !editor.can().undo() })}
          onClick={() => editor.can().undo() && editor.chain().focus().undo().run()}
        >
          <Icons.RotateLeft />
        </span>
        <span
          className={classNames("menu-button", { disabled: !editor.can().redo() })}
          onClick={() => editor.can().redo() && editor.chain().focus().redo().run()}
        >
          <Icons.RotateRight />
        </span>
        <span
          className={classNames("menu-button", {
            "is-active": editor.isActive("bulletList"),
          })}
          onClick={toggleBulletList}
        >
          <Icons.BulletList />
        </span>
        <span
          className={classNames("menu-button", {
            "is-active": editor.isActive("bold"),
          })}
          onClick={toggleBold}
        >
          <Icons.Bold />
        </span>
        <span
          className={classNames("menu-button", {
            "is-active": editor.isActive("italic"),
          })}
          onClick={toggleItalic}
        >
          <Icons.Italic />
        </span>
        <span
          className="menu-button"
          onClick={openColorPicker}
          title="Changer la couleur"
        >
          <Icons.Palette size={18} />
        </span>
        <input
          type="color"
          ref={colorInputRef}
          onChange={applyColor}
          style={{ display: 'none' }}
          aria-label="Sélecteur de couleur"
        />
      </div>

      <EditorContent editor={editor} />
    </div>
  );
}
